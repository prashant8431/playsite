<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Bookie_rate
 *
 * @property int $id
 * @property int $bookie_id
 * @property string $single
 * @property string $jodi
 * @property string $single_patti
 * @property string $double_patti
 * @property string $tripple_patti
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $bookie
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereBookieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereDoublePatti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereJodi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereSingle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereSinglePatti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereTripplePatti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_rate whereUpdatedAt($value)
 */
	class Bookie_rate extends \Eloquent {}
}

namespace App{
/**
 * App\Bookie_referal
 *
 * @property int $id
 * @property int $user_id
 * @property string $referal_code
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereReferalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bookie_referal whereUserId($value)
 */
	class Bookie_referal extends \Eloquent {}
}

namespace App{
/**
 * App\Game
 *
 * @property int $id
 * @property string $name
 * @property string $runningTime
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereRunningTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 */
	class Game extends \Eloquent {}
}

namespace App{
/**
 * App\History
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property string|null $gameName
 * @property string|null $gameType
 * @property string|null $otc
 * @property string|null $played_no
 * @property int|null $points
 * @property string|null $result
 * @property string|null $resultStatus
 * @property string|null $playHistory
 * @property int|null $balance
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|History newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|History newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|History query()
 * @method static \Illuminate\Database\Eloquent\Builder|History whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereGameName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereGameType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereOtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History wherePlayHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History wherePlayedNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereResultStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|History whereUserId($value)
 */
	class History extends \Eloquent {}
}

namespace App{
/**
 * App\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property string $notification
 * @property string|null $by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App{
/**
 * App\Package
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $no_of_users
 * @property int $amount
 * @property int $validity
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereNoOfUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereValidity($value)
 */
	class Package extends \Eloquent {}
}

namespace App{
/**
 * App\Payment
 *
 * @property int $id
 * @property int $user_id
 * @property int $package_id
 * @property int $amount
 * @property int|null $deposite
 * @property string|null $status
 * @property string $paid_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Package $package
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeposite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App{
/**
 * App\Result
 *
 * @property int $id
 * @property int $game_id
 * @property string|null $game_name
 * @property string|null $open
 * @property string|null $close
 * @property string|null $jodi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Game $game
 * @method static \Illuminate\Database\Eloquent\Builder|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereGameName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereJodi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereUpdatedAt($value)
 */
	class Result extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $user_name
 * @property string $email
 * @property string $password
 * @property string $contact_no
 * @property string|null $bank_name
 * @property string|null $acc_no
 * @property string|null $ifsc
 * @property string|null $upi
 * @property string|null $status
 * @property string|null $points
 * @property string $bookie_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBookieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIfsc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserName($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Withdraw
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property string|null $reference
 * @property string|null $remarks
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw query()
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Withdraw whereUserId($value)
 */
	class Withdraw extends \Eloquent {}
}

