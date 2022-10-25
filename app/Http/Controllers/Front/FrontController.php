<?php

namespace App\Http\Controllers\Front;

use App\Models\job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\User;
use App\Models\Gift;
use App\Models\Cantact;
use App\Models\About;
use App\Models\Company;
use App\Models\Event;
use App\Models\Article;
use App\Models\ProductPrice;
use App\Models\Code;

class FrontController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function index()
    {
        $Gifts = Gift::where('status', 'active')->get();
        return view('front.index', compact('Gifts'));
    }

    public function checkphone(Request $request)
    {
        $messages = [
            'mobile.required' => 'شماره تلفن الزامی است',
            'mobile.unique' => 'شماره تکراری است لطفا وارد شوید.',
            'mobile.regex' => 'فرمت شماره شما صحیح نیست',
            'mobile.numeric' => 'فرمت شماره شما صحیح نیست',
        ];
        $this->validate($request, [
            'mobile' => 'required'
        ], $messages);

        $mobile = num_to_en($request->mobile);
        $user = User::where('mobile', $mobile)->first();

        if ($user != null) {
            if ($user->Gift_id == null) {
                $Gifts = Gift::all();

                $code = rand(10000, 99999);
                $user->code = $code;
                $user->save();
                $params = array(
                    "receptor" => $mobile,
                    "message" => "کد تایید : " . $code,
                );
                sendSmsNew($params);
                return view('front.gift.checkCode', compact('Gifts', 'mobile'));


            } else {
                $Gift = Gift::where('id', $user->Gift_id)->first();
                $get = true;
                $code = Code::where('user_id', $user->id)->where('gift_id', $Gift->id)->first();
                return view('front.gift.getfinal', compact('Gift', 'user', 'get','code'));
            }
        } else {
            return redirect()->back()->with('err_message', "شماره اشتباه است");
        }
    }

    public function getcode($id, $phone, Request $request)
    {
        $user = User::where('mobile', $phone)->first();

        if ($user != null) {
            if ($user->Gift_id == null) {


                $Gift = Gift::where('id', $id)->first();

                if ($Gift != null) {
                    if ($Gift->type == 1) {
                        $code = Code::where('use', false)->where('gift_id', $Gift->id)->first();
                        if ($code == null) {
                            $Gifts = Gift::where('status', 'active')->where('category_id', $user->category_id)->get();
                            $mobile = $user->mobile;
                            return view('front.gift.get', compact('Gifts', 'user', 'mobile'));
                        }
                    }

                    if ($Gift->type == 1) {
                        $code->user_id = $user->id;
                        $code->use = true;
                        $code->get_date = jdate("Y/m/d");
                        $code->save();
                    }


                    $user->Gift_id = $id;
                    $user->get_date = jdate("Y/m/d");
                    $user->save();

                    $get = false;
                    if ($Gift->type == 1) {
                        $message='';
                        $message.=$user->first_name.' '.$user->last_name;
                        $message.=" عزیز!";
                        $message.="%0A";
                        $message.="کد خرید شما از ";
                        $message.=$Gift->title;
                        $message.=" هدیه نوروزی حافظ 🎁";
                        $message.="%0A%0A";
                        $message.="کد: ";
                        $message.=$code->code;
                        $message.="%0A";
                        $message.="انقضا: ";
                        $message.=$Gift->expir;
                        $message.="%0A";
                        $message.="لینک سایت خرید: ";
                        $message.=$Gift->link;
                         $message.="%0A%0A";
                         $message.="این عیدی رو حافظ میده";
                        $params = array(
                            "receptor" => $user->mobile,
//                            "message" => "$user->first_name $user->last_name عزیز!
//کد خرید شما از $Gift->title هدیه نوروزی حافظ 🎁
//کد: $code->code
//انقضا: $Gift->expir
//لینک سایت خرید: $Gift->link
//
//این عیدی رو حافظ میده.",
                        "message"=>$message
                        );
                    } elseif ($Gift->type == 2) {
                        $message='';
                        $message.=$user->first_name.' '.$user->last_name;
                        $message.=" عزیز!";
                        $message.="%0A";
                        $message.="درخواست صدور ";
                        $message.=$Gift->title;
                        $message.=" برای شما ثبت شد. در نزدیک ترین زمان ممکن کارشناسان ما برای صدور اقدام کرده و مراتب بعدی به شما اطلاع داده خواهد شد.🎁";
                         $message.="%0A%0A";
                         $message.="این عیدی رو حافظ میده";
                        $params = array(
                            "receptor" => $user->mobile,
//                            "message" => "$user->first_name $user->last_name عزیز!
//درخواست صدور $Gift->title برای شما ثبت شد. در نزدیک ترین زمان ممکن کارشناسان ما برای صدور اقدام کرده و مراتب بعدی به شما اطلاع داده خواهد شد.🎁
//
//این عیدی رو حافظ میده.
//",
                        "message"=>$message
                        );
                    } elseif ($Gift->type == 3) {
                        $message='';
                        $message.=$user->first_name.' '.$user->last_name;
                        $message.=" عزیز!";
                        $message.="%0A";
                        $message.="درخواست شما برای اختصاص هدیه نقدی به ";
                        $message.=$Gift->title;
                        $message.=" دریافت شد. در پایان فروردین ماه با تجمیع درخواست-های ثبت شده، این مبلغ واریز و گزارش آن برای شما ارسال خواهد شد. ممنونیم که به گسترش خدمات آموزشی برای محرومان کمک کردید.";
                         $message.="%0A%0A";
                         $message.="این عیدی رو حافظ میده";
                        $params = array(
                            "receptor" => $user->mobile,
//                            "message" => "$user->first_name $user->last_name عزیز!
//درخواست شما برای اختصاص هدیه نقدی به $Gift->title دریافت شد. در پایان فروردین ماه با تجمیع درخواست-های ثبت شده، این مبلغ واریز و گزارش آن برای شما ارسال خواهد شد. ممنونیم که به گسترش خدمات آموزشی برای محرومان کمک کردید.
//
//این عیدی رو حافظ میده.",
                            "message"=>$message
                        );
                    }
                    sendSmsNew($params);
                    $code = Code::where('user_id', $user->id)->where('gift_id', $Gift->id)->first();
                    return view('front.gift.getfinal', compact('Gift', 'user', 'get', 'code'));
                }
            } else {
                $Gift = Gift::where('id', $user->Gift_id)->first();
                $code = Code::where('user_id', $user->id)->where('gift_id', $Gift->id)->first();
                $get = true;
                return view('front.gift.getfinal', compact('Gift', 'user', 'get', 'code'));
            }
        } else {
            return redirect()->back()->with('err_message', "شماره اشتباه است");
        }
    }

    public function checkCode(Request $request)
    {
        $mobile = $request->mobile;
        $code = num_to_en($request->code);
        $user = User::where('mobile', $mobile)->first();


        if ($user != null) {
            if ($user->code == $code) {

                if ($user->Gift_id == null) {
                    $Gifts = Gift::where('status', 'active')->orderBy('sort','asc')->where('category_id', $user->category_id)->get();

                    return view('front.gift.get', compact('Gifts', 'user', 'mobile'));
                } else {
                    $Gift = Gift::where('id', $user->Gift_id)->first();
                    $get = true;
                    return view('front.gift.getfinal', compact('Gift', 'user', 'get'));
                }

            } else {
                $Gifts = Gift::where('status', 'active')->get();
                $err_message = "کد وارد شده اشتباه است";
                //return view('front.gift.checkCode', compact('Gifts', 'mobile', 'err_message'));
                Session()->flash('err_message', $err_message);

                return redirect()->route('front-index');
            }
        } else {
            return redirect()->back()->with('err_message', "مشکلی پیش آمده است");
        }

    }

}
