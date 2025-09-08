<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_image',
        'post_number',
        'address',
        'address_country',
        'building',
        'stripe_id',
        'first_time_access',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Stripeに渡す顧客情報をカスタマイズします。
     * このメソッドを上書きすることで、Stripeに送信する情報を追加できます。
     *
     * @return array
     */
    public function toStripeCustomerArray()
    {
        // データベースから住所データを取得し、trim()で空白文字を削除します
        $address = array_filter([
            'country' => trim($this->address_country ?? ''),
            'line1' => trim($this->address ?? ''),
            'postal_code' => trim($this->post_number ?? ''),
        ]);

        // 住所データが空でなければ、Stripeに送信する配列に含めます。
        $customer = array_filter([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // $addressが空ではないことを確認してから追加します
        if (!empty($address)) {
            $customer['address'] = $address;
        }

        return $customer;
    }
}