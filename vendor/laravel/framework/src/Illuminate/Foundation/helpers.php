<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Foundation\Bus\PendingClosureDispatch;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpFoundation\Response;
if (!function_exists('num_to_en')) {
    function num_to_en($data)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $output= str_replace($persian, $english, $data);
        return $output;
    }
}
if (!function_exists('jdate')) {
    function jdate($format,$timestamp='',$none='',$time_zone='Asia/Tehran',$tr_num='fa'){

        $T_sec=0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

        if($time_zone!='local')date_default_timezone_set(($time_zone==='')?'Asia/Tehran':$time_zone);
        $ts=$T_sec+(($timestamp==='')?time():tr_num($timestamp));
        $date=explode('_',date('H_i_j_n_O_P_s_w_Y',$ts));
        list($j_y,$j_m,$j_d)=gregorian_to_jalali($date[8],$date[3],$date[2]);
        $doy=($j_m<7)?(($j_m-1)*31)+$j_d-1:(($j_m-7)*30)+$j_d+185;
        $kab=(((($j_y%33)%4)-1)==((int)(($j_y%33)*0.05)))?1:0;
        $sl=strlen($format);
        $out='';
        for($i=0; $i<$sl; $i++){
            $sub=substr($format,$i,1);
            if($sub=='\\'){
                $out.=substr($format,++$i,1);
                continue;
            }
            switch($sub){

                case'E':case'R':case'x':case'X':
                $out.='http://jdf.scr.ir';
                break;

                case'B':case'e':case'g':
                case'G':case'h':case'I':
                case'T':case'u':case'Z':
                $out.=date($sub,$ts);
                break;

                case'a':
                    $out.=($date[0]<12)?'ق.ظ':'ب.ظ';
                    break;

                case'A':
                    $out.=($date[0]<12)?'قبل از ظهر':'بعد از ظهر';
                    break;

                case'b':
                    $out.=(int)($j_m/3.1)+1;
                    break;

                case'c':
                    $out.=$j_y.'/'.$j_m.'/'.$j_d.' ،'.$date[0].':'.$date[1].':'.$date[6].' '.$date[5];
                    break;

                case'C':
                    $out.=(int)(($j_y+99)/100);
                    break;

                case'd':
                    $out.=($j_d<10)?'0'.$j_d:$j_d;
                    break;

                case'D':
                    $out.=jdate_words(array('kh'=>$date[7]),' ');
                    break;

                case'f':
                    $out.=jdate_words(array('ff'=>$j_m),' ');
                    break;

                case'F':
                    $out.=jdate_words(array('mm'=>$j_m),' ');
                    break;

                case'H':
                    $out.=$date[0];
                    break;

                case'i':
                    $out.=$date[1];
                    break;

                case'j':
                    $out.=$j_d;
                    break;

                case'J':
                    $out.=jdate_words(array('rr'=>$j_d),' ');
                    break;

                case'k';
                    $out.=tr_num(100-(int)($doy/($kab+365)*1000)/10,$tr_num);
                    break;

                case'K':
                    $out.=tr_num((int)($doy/($kab+365)*1000)/10,$tr_num);
                    break;

                case'l':
                    $out.=jdate_words(array('rh'=>$date[7]),' ');
                    break;

                case'L':
                    $out.=$kab;
                    break;

                case'm':
                    $out.=($j_m>9)?$j_m:'0'.$j_m;
                    break;

                case'M':
                    $out.=jdate_words(array('km'=>$j_m),' ');
                    break;

                case'n':
                    $out.=$j_m;
                    break;

                case'N':
                    $out.=$date[7]+1;
                    break;

                case'o':
                    $jdw=($date[7]==6)?0:$date[7]+1;
                    $dny=364+$kab-$doy;
                    $out.=($jdw>($doy+3) and $doy<3)?$j_y-1:(((3-$dny)>$jdw and $dny<3)?$j_y+1:$j_y);
                    break;

                case'O':
                    $out.=$date[4];
                    break;

                case'p':
                    $out.=jdate_words(array('mb'=>$j_m),' ');
                    break;

                case'P':
                    $out.=$date[5];
                    break;

                case'q':
                    $out.=jdate_words(array('sh'=>$j_y),' ');
                    break;

                case'Q':
                    $out.=$kab+364-$doy;
                    break;

                case'r':
                    $key=jdate_words(array('rh'=>$date[7],'mm'=>$j_m));
                    $out.=$date[0].':'.$date[1].':'.$date[6].' '.$date[4].' '.$key['rh'].'، '.$j_d.' '.$key['mm'].' '.$j_y;
                    break;

                case's':
                    $out.=$date[6];
                    break;

                case'S':
                    $out.='ام';
                    break;

                case't':
                    $out.=($j_m!=12)?(31-(int)($j_m/6.5)):($kab+29);
                    break;

                case'U':
                    $out.=$ts;
                    break;

                case'v':
                    $out.=jdate_words(array('ss'=>($j_y%100)),' ');
                    break;

                case'V':
                    $out.=jdate_words(array('ss'=>$j_y),' ');
                    break;

                case'w':
                    $out.=($date[7]==6)?0:$date[7]+1;
                    break;

                case'W':
                    $avs=(($date[7]==6)?0:$date[7]+1)-($doy%7);
                    if($avs<0)$avs+=7;
                    $num=(int)(($doy+$avs)/7);
                    if($avs<4){
                        $num++;
                    }elseif($num<1){
                        $num=($avs==4 or $avs==((((($j_y%33)%4)-2)==((int)(($j_y%33)*0.05)))?5:4))?53:52;
                    }
                    $aks=$avs+$kab;
                    if($aks==7)$aks=0;
                    $out.=(($kab+363-$doy)<$aks and $aks<3)?'01':(($num<10)?'0'.$num:$num);
                    break;

                case'y':
                    $out.=substr($j_y,2,2);
                    break;

                case'Y':
                    $out.=$j_y;
                    break;

                case'z':
                    $out.=$doy;
                    break;

                default:$out.=$sub;
            }
        }
        return($tr_num!='en')?tr_num($out,'fa','.'):$out;
    }
}
function gregorian_to_jalali($gy,$gm,$gd,$mod=''){
    list($gy,$gm,$gd)=explode('_',tr_num($gy.'_'.$gm.'_'.$gd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
    $g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
    if($gy > 1600){
        $jy=979;
        $gy-=1600;
    }else{
        $jy=0;
        $gy-=621;
    }
    $gy2=($gm > 2)?($gy+1):$gy;
    $days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) +((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
    $jy+=33*((int)($days/12053));
    $days%=12053;
    $jy+=4*((int)($days/1461));
    $days%=1461;
    $jy+=(int)(($days-1)/365);
    if($days > 365)$days=($days-1)%365;
    if($days < 186){
        $jm=1+(int)($days/31);
        $jd=1+($days%31);
    }else{
        $jm=7+(int)(($days-186)/30);
        $jd=1+(($days-186)%30);
    }
    return($mod==='')?array($jy,$jm,$jd):$jy .$mod .$jm .$mod .$jd;
    
}
function tr_num($str,$mod='en',$mf='٫'){
    $num_a=array('0','1','2','3','4','5','6','7','8','9','.');
    $key_a=array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹',$mf);
    return($mod=='fa')?str_replace($num_a,$key_a,$str):str_replace($key_a,$num_a,$str);
}
if (!function_exists('sendSms')) {

    function sendSms($data = null)
    {
        $url = "https://api.kavenegar.com/v1/494F41326957307942515857705A446E32426C77664F417130626161374562487648304463346A2B476E6F3D/sms/send.json";
        $headers       = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'charset: utf-8'
        );
        $fields_string = "";
        if (!is_null($data)) {
            $fields_string = http_build_query($data);
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

        $response     = curl_exec($handle);
        $code         = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $curl_errno   = curl_errno($handle);
        $curl_error   = curl_error($handle);
    }
}
if (!function_exists('sendSmsNew')) {

    function sendSmsNew($data=null)
    {
        $to = $data["receptor"];
        $Text = str_replace(' ','%20',$data["message"]);
        $url = "http://ws.nh1.ir/Api/SMS/Send?Username=hafezbourse&Password=T@DB!R89306767&Text=$Text&To=$to&From=500090213";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        return $resp;
    }
}

if (!function_exists('direction')) {
    function direction()
    {
        switch (app()->currentLocale()){
            case 'fa':
            case 'ar':
                return 'rtl';
                break;
            default:
                return 'ltr';
                break;
        }
    }
}
if (!function_exists('text_align')) {
    function text_align($rtl=false)
    {
        switch (app()->currentLocale()){
            case 'fa':
            case 'ar':
                return $rtl?'text-left':'text-right';
            default:
                return $rtl?'text-right':'text-left';
        }
    }
}
if (!function_exists('text_align_class')) {
    function text_align_class($rtl=false)
    {
        switch (app()->currentLocale()){
            case 'fa':
            case 'ar':
                return $rtl?'text-left':'text-right';
            default:
                return $rtl?'text-right':'text-left';
        }
    }
}
if (!function_exists('rotate_transform')) {
    function rotate_transform()
    {
        switch (app()->currentLocale()){
            case 'fa':
            case 'ar':
                return '';
            default:
                return 'left-rotate';
        }
    }
}
if (!function_exists('siteTitle')) {
    function siteTitle()
    {
        return 'Adib';
    }
}
if (!function_exists('defaultProfilePicture')) {
    function defaultProfilePicture()
    {
        return assets('dashboard/images/portrait/small/avatar-s-11.jpg');
    }
}

if (!function_exists('fullName')) {
    function fullName($user=null)
    {
        if(!is_null($user)){
            return $user->first_name.' '.$user->last_name;
        }
        $user=auth()->user();
        return $user->first_name.' '.$user->last_name;
    }
}

if (!function_exists('firstName')) {
    function firstName($user=null)
    {
        if(!is_null($user)){
            return $user->first_name;
        }
        $user=auth()->user();
        return $user->first_name;
    }
}
if (!function_exists('lastName')) {
    function lastName($user=null)
    {
        if(!is_null($user)){
            return $user->last_name;
        }
        $user=auth()->user();
        return $user->last_name;
    }
}

if (! function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof Response) {
            throw new HttpResponseException($code);
        } elseif ($code instanceof Responsable) {
            throw new HttpResponseException($code->toResponse(request()));
        }

        app()->abort($code, $message, $headers);
    }
}

if (! function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (! function_exists('abort_unless')) {
    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param  bool  $boolean
     * @param  \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int  $code
     * @param  string  $message
     * @param  array  $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_unless($boolean, $code, $message = '', array $headers = [])
    {
        if (! $boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (! function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string|array  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return app('url')->action($name, $parameters, $absolute);
    }
}

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path($path);
    }
}

if (! function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (! function_exists('assets')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function assets($path)
    {
        return url('/').'/'.env('ASSET_URL').('/'.$path);
    }
}

if (!function_exists('file_store')) {
    function file_store($u_file, $u_path, $u_prefix)
    {
        $file = $u_file;
        $originalName = $u_file->getClientOriginalName();
        $destinationPath = $u_path;
        $extension = $file->getClientOriginalExtension();
        $fileName = $u_prefix . md5(time() . '-' . $originalName) . '.' . $extension;
        $file->move($destinationPath, $fileName);
        $f_path = $destinationPath . "" . $fileName;
        return $f_path;
    }
}

if (! function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }

        return app(AuthFactory::class)->guard($guard);
    }
}

if (! function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int  $status
     * @param  array  $headers
     * @param  mixed  $fallback
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false)
    {
        return app('redirect')->back($status, $headers, $fallback);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath($path);
    }
}

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (! function_exists('broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param  mixed|null  $event
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    function broadcast($event = null)
    {
        return app(BroadcastFactory::class)->event($event);
    }
}

if (! function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param  dynamic  key|key,default|data,expiration|null
     * @return mixed|\Illuminate\Cache\CacheManager
     *
     * @throws \Exception
     */
    function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return app('cache');
        }

        if (is_string($arguments[0])) {
            return app('cache')->get(...$arguments);
        }

        if (! is_array($arguments[0])) {
            throw new Exception(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return app('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1] ?? null);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->configPath($path);
    }
}

if (! function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param  string|null  $name
     * @param  string|null  $value
     * @param  int  $minutes
     * @param  string|null  $path
     * @param  string|null  $domain
     * @param  bool|null  $secure
     * @param  bool  $httpOnly
     * @param  bool  $raw
     * @param  string|null  $sameSite
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = app(CookieFactory::class);

        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}

if (! function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="'.csrf_token().'">');
    }
}

if (! function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (! function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string  $path
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @param  bool  $unserialize
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (! function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    function dispatch($job)
    {
        return $job instanceof Closure
                ? new PendingClosureDispatch(CallQueuedClosure::create($job))
                : new PendingDispatch($job);
    }
}

if (! function_exists('dispatch_sync')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * Queueable jobs will be dispatched to the "sync" queue.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     */
    function dispatch_sync($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchSync($job, $handler);
    }
}

if (! function_exists('dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $job
     * @param  mixed  $handler
     * @return mixed
     *
     * @deprecated Will be removed in a future Laravel version.
     */
    function dispatch_now($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchNow($job, $handler);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}

if (! function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (! function_exists('info')) {
    /**
     * Write some information to the log.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    function info($message, $context = [])
    {
        app('log')->info($message, $context);
    }
}

if (! function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return \Illuminate\Log\LogManager|null
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message, $context);
    }
}

if (! function_exists('logs')) {
    /**
     * Get a log driver instance.
     *
     * @param  string|null  $driver
     * @return \Illuminate\Log\LogManager|\Psr\Log\LoggerInterface
     */
    function logs($driver = null)
    {
        return $driver ? app('log')->driver($driver) : app('log');
    }
}

if (! function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param  string  $method
     * @return \Illuminate\Support\HtmlString
     */
    function method_field($method)
    {
        return new HtmlString('<input type="hidden" name="_method" value="'.$method.'">');
    }
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        return app(Mix::class)(...func_get_args());
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}

if (! function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}

if (! function_exists('policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param  object|string  $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    function policy($class)
    {
        return app(Gate::class)->getPolicyFor($class);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public').($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (! function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (! function_exists('report')) {
    /**
     * Report an exception.
     *
     * @param  \Throwable|string  $exception
     * @return void
     */
    function report($exception)
    {
        if (is_string($exception)) {
            $exception = new Exception($exception);
        }

        app(ExceptionHandler::class)->report($exception);
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Http\Request|string|array|null
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (! function_exists('rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param  callable  $callback
     * @param  mixed  $rescue
     * @param  bool  $report
     * @return mixed
     */
    function rescue(callable $callback, $rescue = null, $report = true)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                report($e);
            }

            return value($rescue, $e);
        }
    }
}

if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     */
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (! function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param  \Illuminate\Contracts\View\View|string|array|null  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = app(ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        return app('url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('secure_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @return string
     */
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if (! function_exists('secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param  string  $path
     * @param  mixed  $parameters
     * @return string
     */
    function secure_url($path, $parameters = [])
    {
        return url($path, $parameters, true);
    }
}

if (! function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Session\Store|\Illuminate\Session\SessionManager
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function today($tz = null)
    {
        return Date::today($tz);
    }
}

if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        return app('translator')->get($key, $replace, $locale);
    }
}

if (! function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return app('translator')->choice($key, $number, $replace, $locale);
    }
}

if (! function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return trans($key, $replace, $locale);
    }
}

if (! function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}

if (! function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Contracts\Validation\Factory
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = app(ValidationFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}
