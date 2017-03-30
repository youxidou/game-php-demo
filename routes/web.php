<?php
use Yxd\Game\Foundation\Application;
use Yxd\Game\Payment\Order;

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

Route::get('/', function () {
    $config = config('pay');
    $app = new Application($config);

    try {
        $user = $app->user->get()->all();
    } catch (\Exception $e) {
        return '请先登录';
    }

    setUser($user);

    $user['money'] = money();

    return view('welcome', $user);
});

Route::get('/pay/{money}', function ($money) {
    incrementMoney($money);

    return redirect()->to('/');
});

Route::get('/order', function (\Illuminate\Http\Request $request) {
    $money_id = $request->get('money_id');
    $moneys = [0.01, 0.1, 1, 10, 100];
    if (!isset($moneys[$money_id - 1])) {
        return response()->json(['msg' => '下单错误'], 400);
    }
    $money = $moneys[$money_id - 1];

    $config = config('pay');
    $config['notify_url'] = route('notify');
    $app = new Application($config);
    $user = user();

    $attributes = [
        'open_id'       => $user['open_id'],
        'money'         => $money,
        'game_order_no' => tradeNo(),
        'title'         => "充值{$money}元",
        'description'   => "充值{$money}元",
    ];
    $order = new Order($attributes);
    $result = $app->payment->prepare($order);

    $config = $app->payment->configForPay($result['prepay_id'], false);

    return response()->json($config);
});

Route::match(['GET', 'POST'], '/notify', function (\Illuminate\Http\Request $request) {
    $config = config('pay');
    $app = new Application($config);
    $result = $app->payment->handleNotify(function ($notify, $successful) {
        if ($successful) {
            $game_order_no = $notify->game_order_no;
            paySuccess($game_order_no, $notify->money);

            return true;
        }
    });

    return $result;
})->name('notify');

function user()
{
    return session('user');
}

function setUser($user)
{
    session()->setId(createSessionId($user['open_id']));
    session()->start();
    session()->put('user', $user);
}

function money()
{
    return ((int)(session('money') * 100)) / 100;
}

function incrementMoney($money)
{
    session()->increment('money', ((int)($money * 100)) / 100);
}

function tradeNo()
{
    return session()->getId() . '|' . str_random(9);
}

function paySuccess($trade_no, $money)
{
    list($session_id) = explode('|', $trade_no);
    session()->setId($session_id);
    session()->start();
    incrementMoney($money);
}

function createSessionId($id)
{
    return md5($id) . '11111111';
}
