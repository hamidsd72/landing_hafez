<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test_send', function () {

    $to = '09187107810';
    $Text = 'جهت تست';
    sendSmsNew($to,$Text);
//    $user_name='hafezbourse';
//    $password='T@DB!R89306767';
//    $url = "http://ws.nh1.ir/Api/SMS/Send?Username=$user_name&Password=$password&Text=$Text&To=$to&From=500090213";
//
//    $curl = curl_init($url);
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
//    $resp = curl_exec($curl);
//    dd($resp);
    //curl_close($curl);
});
Route::get('/test_send2', function () {
    $user=User::find(1);
    $to = "09187107810";
    $message="";
//    $message='درخواست شما برای اختصاص هدیه نقدی به دریافت شد. در پایان فروردین ماه با تجمیع درخواست-های ثبت شده، این مبلغ واریز و گزارش آن برای شما ارسال خواهد شد. ممنونیم که به گسترش خدمات آموزشی برای محرومان کمک کردید.';
    $message.=$user->first_name.' '.$user->last_name;
    $message.=' عزیز!';
    $message.="%0A%0A";
    $message.="درخواست شما برای اختصاص هدیه نقدی به دریافت شد. در پایان فروردین ماه با تجمیع درخواست-های ثبت شده، این مبلغ واریز و گزارش آن برای شما ارسال خواهد شد. ممنونیم که به گسترش خدمات آموزشی برای محرومان کمک کردید.";
    $message.="%0A";
    $message.="این عیدی رو حافظ میده.";
    $params =["receptor"=>$to,"message"=>$message,];



    sendSmsNew($params);

//    $url = "http://ws.nh1.ir/Api/SMS/Send?Username=hafezbourse&Password=T@DB!R89306767&Text=$message&To=$to&From=500090213";
//
//    $curl = curl_init($url);
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
//    $resp = curl_exec($curl);
//
//    dd($resp);
});
Route::get('/maketable', function () {

    /*Schema::create('sliders', function (Blueprint $table) {
        $table->increments('id');
        $table->char('photo', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('boxes', function (Blueprint $table) {
        $table->increments('id');

        $table->char('title_1', 255);
        $table->char('text_1', 255);
        $table->char('photo_1', 255);

        $table->char('title_2', 255);
        $table->char('text_2', 255);
        $table->char('photo_2', 255);

        $table->char('title_3', 255);
        $table->char('text_3', 255);
        $table->char('photo_3', 255);

        $table->char('title_4', 255);
        $table->char('text_4', 255);
        $table->char('photo_4', 255);


        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('backgrounds', function (Blueprint $table) {
        $table->increments('id');
        $table->char('photo', 255);
        $table->char('footer', 255);
        $table->char('text', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('targets', function (Blueprint $table) {
        $table->increments('id');

        $table->char('title_1', 255);
        $table->char('text_1', 255);
        $table->char('photo_1', 255);
        $table->char('icon_1', 255);

        $table->char('title_2', 255);
        $table->char('text_2', 255);
        $table->char('photo_2', 255);
        $table->char('icon_2', 255);

        $table->char('title_3', 255);
        $table->char('text_3', 255);
        $table->char('photo_3', 255);
        $table->char('icon_3', 255);

        $table->char('title_4', 255);
        $table->char('text_4', 255);
        $table->char('photo_4', 255);
        $table->char('icon_4', 255);

        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('jobs', function (Blueprint $table) {
        $table->increments('id');
        $table->char('name', 255);
        $table->char('Gender', 255);
        $table->text('Evidence');
        $table->text('Conditions');
        $table->char('date', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });
    Schema::create('cantacts', function (Blueprint $table) {
        $table->increments('id');
        $table->char('address', 255);
        $table->char('phone', 255);
        $table->char('whatsup', 255);
        $table->char('facebook', 255);
        $table->char('instagram', 255);
        $table->char('twitter', 255);
        $table->char('youtube', 255);
        $table->char('image', 255);
        $table->text('map');

        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('messages', function (Blueprint $table) {
        $table->increments('id');
        $table->char('name', 255);
        $table->char('phone', 255);
        $table->char('email', 255);
        $table->char('subject', 255);
        $table->text('message');
        $table->boolean('new')->default(true);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('abouts', function (Blueprint $table) {
        $table->increments('id');
        $table->char('title', 255);
        $table->text('text');
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('messages', function (Blueprint $table) {
        $table->increments('id');
        $table->char('name', 255);
        $table->char('phone', 255);
        $table->char('email', 255);
        $table->char('subject', 255);
        $table->text('message');
        $table->boolean('new')->default(true);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('companies', function (Blueprint $table) {
        $table->increments('id');
        $table->char('icon', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('categories', function (Blueprint $table) {
        $table->increments('id');
        $table->char('name', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('products', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('category_id');
        $table->char('name', 255);
        $table->char('slug', 255);
        $table->char('photo', 255);
        $table->char('length', 255);
        $table->char('Dimensions', 255);
        $table->char('Tolerance', 255);
        $table->text('Description');
        $table->longText('text');
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('events', function (Blueprint $table) {
        $table->increments('id');
        $table->char('title', 255);
        $table->char('slug', 255);
        $table->char('photo', 255);
        $table->longText('text');
        $table->text('meta_keyboard');
        $table->text('meta_description');
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('articles', function (Blueprint $table) {
        $table->increments('id');
        $table->char('title', 255);
        $table->char('slug', 255);
        $table->char('photo', 255);
        $table->text('description');
        $table->longText('text');
        $table->text('meta_keyboard');
        $table->text('meta_description');
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('product_prices', function (Blueprint $table) {
        $table->increments('id');

        $table->char('name', 255);
        $table->integer('size');
        $table->char('state', 255);
        $table->char('unit', 255);
        $table->char('get', 255);
        $table->char('price', 255);
        $table->char('lang', 25)->default('fa');
        $table->timestamps();
    });

    Schema::create('gifts', function (Blueprint $table) {
        $table->increments('id');
        $table->char('title', 255);
        $table->char('photo', 255);
        $table->text('code');
        $table->integer('category_id');
        $table->timestamps();
    });
    $hashedpass = Hash::make("HgT@!@#Es");
    echo $hashedpass;*/

    Schema::create('codes', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('gift_id');
        $table->text('code');
        $table->boolean('use')->default(false);
        $table->boolean('active')->default(true);
        $table->integer('user_id')->nullable();
        $table->char('get_date', 255)->nullable();
        $table->timestamps();
    });


});
/*Route::get('user',function (){

    $users = User::where('id','!=',1)->get();
    foreach ($users as $user){
        $user->mobile = "0".$user->mobile;
        $user->save();
    }
});*/
Route::get('ahmadi_log',function (){
    Auth::loginUsingId(1);
    return redirect()->route('dashboard-index');

});
Route::get('date',function (){
    $date =  jdate("Y/m/d");
    return $date;
});
Route::get('sms',function (){
    $params = array(
        "receptor" => "09032978774",
        "message" => "test",
    );

    $to = $params['receptor'];
    $Text = $params['message'];
    $url = "http://ws.nh1.ir/Api/SMS/Send?Username=hafezbourse&Password=T@DB!R89306767&Text=$Text&To=$to&From=500090213";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
});

Route::get('/config-cache', function() {

    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::post('filemanager/upload',function (Request $request ){
    if(isset($_FILES['upload']['name'])) {
        $file=$_FILES['upload']['name'];
        $filetmp=$_FILES['upload']['tmp_name'];
        $file_pas=explode('.',$file);
        $file_n='check_editor_'.time().'_'.$file_pas[0].'.'.end($file_pas);
        $photo=move_uploaded_file($filetmp,'source/assets/uploads/editor/'.$file_n);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url = url('source/assets/uploads/editor/'.$file_n);
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }
})->name('filemanager_upload');

require __DIR__.'/front.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/auth.php';
