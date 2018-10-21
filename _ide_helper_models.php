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
 * App\Post
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Il[] $ils
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post countLikes()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentDescription()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentVideo()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post currentWord()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deactiveReviews()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedDescriptions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedVideos()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post deletedWords()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post ilRank()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post il()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post inReview()
 */
	class Post extends \Eloquent {}
}

namespace App{
/**
 * App\Review
 *
 * @property int $id
 * @property int $il_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Il $il
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $voters
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Review onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review post()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereIlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Review withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Review withoutTrashed()
 * @mixin \Eloquent
 */
	class Review extends \Eloquent {}
}

namespace App{
/**
 * App\Tag
 *
 * @property int $id
 * @property string $tag
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Tag onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereDeletedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTags( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Tag withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Tag withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sign[] $signs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag getQueriedTag($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tag whereTag($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 */
	class Tag extends \Eloquent {}
}

namespace App{
/**
 * App\Il
 *
 * @property-read \App\Post $post
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Il onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Il withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Il withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property int $id
 * @property int $post_id
 * @property int $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Il whereUpdatedAt($value)
 */
	class Il extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $blacklisted
 * @property string|null $reason
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Description[] $descriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $likes
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Qcv[] $qcvs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionAuthor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotionVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $requestWords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewAuthor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviewVotings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $words
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User qcv()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBlacklisted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User isAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
 * @property string|null $ban_reason
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBanReason($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Remotion
 *
 * @property int $id
 * @property int $user_id
 * @property int $promotion
 * @property string|null $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $voters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion wherePromotion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereDeletedAt($value)
 * @property int $QCV_id
 * @property-read \App\Qcv $Qcv
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereQCVId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Remotion withoutTrashed()
 * @property-read \App\User $user
 * @property int $qcv_id
 * @property-read \App\Qcv $QCV
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Remotion whereQcvId($value)
 */
	class Remotion extends \Eloquent {}
}

namespace App{
/**
 * App\Video
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $video_uuid
 * @property string $camera_uuid
 * @property string $recorded_from
 * @property string $video_url
 * @property string $thumbnail_url
 * @property string $small_thumbnail_url
 * @property int $playings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Post $post
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Video onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCameraUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video wherePlayings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereRecordedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereSmallThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereThumbnailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVideoUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Video withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video getOnlyTrashed()
 */
	class Video extends \Eloquent {}
}

namespace App{
/**
 * App\Description
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Post $post
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Description onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Description withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Description withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description isTagged()
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Description whereText($value)
 */
	class Description extends \Eloquent {}
}

namespace App{
/**
 * App\Qcv
 *
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Qcv withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Remotion[] $remotions
 * @property int $id
 * @property int $user_id
 * @property int $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Qcv whereUserId($value)
 */
	class Qcv extends \Eloquent {}
}

namespace App{
/**
 * App\Word
 *
 * @property int $id
 * @property string $word
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $alias_children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Word[] $alias_parents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $requests
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word getQueriedWord($word = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word latest($num = 25)
 * @method static \Illuminate\Database\Query\Builder|\App\Word onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word random($num = 1, $count = null)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereWord($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Word withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word withoutSign()
 * @method static \Illuminate\Database\Query\Builder|\App\Word withoutTrashed()
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Word whereUserId($value)
 */
	class Word extends \Eloquent {}
}

