<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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
$admin = 'padmin';

Route::post('socket/auth','WSAuthController@authRouter')->middleware('auth:api');
Route::get('cron/suspend/accounts','Cron\EmailSuspendController@suspend');

Route::get('sss',static function(){
    return view('sss');
})->name('user.sss');
Route::get('kullanim-kosullari',function (){
    return view('terms-of-service');
})->name('terms');

Route::get('/payment-list','Web\HomeController@paymentConfirm');
Route::get('/son-odemeler','Web\HomeController@paymentConfirm');
Route::get('/api/service/samurai/{id}','Services\AppSamurai@callbackService');

Route::group(['namespace' => 'Web'], static function(){

    Route::get('/login','AuthController@login')->middleware('guest')->name('user.login');
    Route::post('/login','AuthController@_login')->middleware('guest')->name('user.login.post');
    Route::get('/logout','AuthController@logout')->name('user.logout');
    Route::get('/forgot-password','AuthController@forgotPassword')->middleware('guest')->name('user.forgot.password');

    Route::get('/password/reset/{key}','AuthController@resetPassword')->name('user.password.reset');
    Route::post('/password/reset/{key}','AuthController@_resetPassword')->name('user.password.reset.post');

    Route::get('/account/confirm/{key}','AuthController@confirmEmail')->name('user.email.confirm');
   // Route::get('/account/send/confirm/{mail}','AuthController@sendConfirmationManual')->name('user.email.confirm.send');

    Route::get('/','HomeController@index')->name('user.welcome');
    Route::get('privacy-policy','HomeController@privacy')->name('user.privacy');
    Route::get('/blog/{slug}','BlogController@get')->name('user.blog.view');
//    Route::get('how-to-play','HomeController@howToPlay')->name('user.howToPlay');
});

## User
// Auth With Token
Route::get('/access/oauth2/{token}','AuthController@authWithBearer')->name('user.login.access');
Route::group(['namespace' => 'Web', 'middleware' => 'auth'],function(){
    Route::get('/me','UserInsightController@index')->name('user.insight');
    Route::post('/add/question','UserInsightController@addQuestion')->name('user.add.question');
});
#-- sms verification
Route::group(['middleware' => ['auth','phone.verification']],static function(){
    Route::get('/verify/phone','PhoneVerificationController@sendSMS')->name('user.sms.verify');
    Route::post('/verify/phone','PhoneVerificationController@_sendSMS')->name('user.sms.verify.post');
    Route::get('/verify/phone/confirm','PhoneVerificationController@verify')->name('user.sms.confirm');
    Route::post('/verify/phone/confirm','PhoneVerificationController@_verify')->name('user.sms.confirm.post');
});


## EndUser



Route::get($admin.'/login','Admin\AuthController@login')->name('admin.login')->middleware('guest:admin');
Route::post($admin.'/login','Admin\AuthController@doLogin')->name('admin.login.post')->middleware('guest:admin');

Route::group(['prefix' => $admin,  'middleware' => 'admin','namespace' => 'Admin'], static function(){
    Route::get('home','HomeController@index')->name('admin.home');
    Route::post('insight','HomeController@insight')->name('admin.insight');
    Route::get('missions','MissionController@missions')->name('admin.missions');
    Route::get('missions/add','MissionController@addMission')->name('admin.missions.add');
    Route::post('missions/add','MissionController@doAddMission')->name('admin.missions.add.post');
    Route::get('missions/edit/{id}','MissionController@editMission')->name('admin.missions.edit');
    Route::post('missions/edit/{id}','MissionController@doEditMission')->name('admin.missions.edit.post');
    Route::get('missions/taken','MissionController@takenMissions')->name('admin.missions.taken');
    Route::get('missions/check','MissionController@checkMissions')->name('admin.missions.check');
    Route::post('missions/check','MissionController@doCheckMissions')->name('admin.missions.check.post');
    Route::post('missions/delete','MissionController@hardDelete')->name('admin.missions.delete');

    Route::get('/competitions','Competition\CompetitionController@competitions')->name('admin.competitions');
    Route::get('/competitions/add','Competition\CompetitionController@add')->name('admin.competitions.add');
    Route::post('/competitions/add','Competition\CompetitionController@_add')->name('admin.competitions.add.post');
    Route::post('/competitions/delete','Competition\CompetitionController@_delete')->name('admin.competitions.delete');
    Route::get('/competitions/{id}','Competition\CompetitionController@update')->name('admin.competitions.update');
    Route::post('/competitions/{id}','Competition\CompetitionController@_update')->name('admin.competitions.update.post');

    Route::post('/images/list','GalleryController@images')->name('admin.gallery.images');
    Route::post('/images/delete','GalleryController@_delete')->name('admin.gallery.images.delete');

    Route::get('users','UserController@users')->name('admin.users');
    Route::get('users/{id}','UserController@editUser')->name('admin.users.edit');
    Route::post('users/{id}','UserController@doEditUser')->name('admin.users.edit.post');
    Route::post('users/change-password/{id}','UserController@changePassword')->name('admin.users.change.password');
    Route::post('users/suspend/account','UserController@suspend')->name('admin.users.suspend');
    Route::post('user/verify','UserController@verifyUser')->name('admin.verify.user');
    Route::post('remove/reference','UserController@deleteReference')->name('admin.users.reference.delete');

    Route::get('payment-requests','PaymentController@paymentRequests')->name('admin.payment.requests');
    Route::post('payment-requests','PaymentController@updateRequest')->name('admin.payment.requests.update');
    Route::get('payment-requests/{id}','PaymentController@getPayment')->name('admin.payment.requests.get');

    Route::get('push-notification','PushNotificationController@notification')->name('admin.notification');
    Route::post('push-notification','PushNotificationController@pushNotification')->name('admin.notification.post');

    Route::get('tickets','TicketsController@tickets')->name('admin.tickets');
    Route::get('tickets/{id}','TicketsController@showTicket')->name('admin.tickets.show');
    Route::post('tickets/{id}/answer','TicketsController@answerTicket')->name('admin.tickets.answer');
    Route::post('tickets/{id}/change-status','TicketsController@changeStatus')->name('admin.tickets.status');
    Route::post('ticket/reload','TicketsController@previousReload')->name('admin.tickets.previous');

    Route::get('blog','BlogController@blogs')->name('admin.blogs');
    Route::get('blog/add','BlogController@add')->name('admin.blogs.add');
    Route::post('blog/add','BlogController@_add')->name('admin.blogs.add.post');

    Route::get('blog/edit/{id}','BlogController@edit')->name('admin.blogs.edit');
    Route::post('blog/edit/{id}','BlogController@_edit')->name('admin.blogs.edit.post');

    Route::post('blog/upload/image','BlogController@_upload')->name('admin.upload_blog_image');

    Route::get('support/questions','QuestionSupportController@question')->name('admin.support.questions');
    Route::post('support/questions/delete','QuestionSupportController@_delete')->name('admin.support.questions.delete');
    Route::post('support/questions/add','QuestionSupportController@_add')->name('admin.support.questions.add');
    Route::get('support/questions/edit/{id}','QuestionSupportController@edit')->name('admin.support.questions.edit');
    Route::post('support/questions/edit/{id}','QuestionSupportController@_edit')->name('admin.support.questions.edit.post');

    Route::get('logout','AuthController@logout')->name('admin.logout');
});

// Only test avatar
Route::get('avatar/{filename}', function ($filename)
{
    $path = storage_path('app/avatar/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }



    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
