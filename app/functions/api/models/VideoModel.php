<?php

use Timber\Timber;

class VideoModel{
    
    public function __construct(){

    }

    /**
     * Methods
     */
    //3. Videos ---------------------------------------//
    public static function getAll($request){
        if( isset($request['query']) ){
            $video_args = [
                "post_type" => "video",
                "posts_per_page" => -1,
                'orderby' => 'post_date',
                "order" => "ASC",
                's' => $request['query']
            ];
    
            return get_posts($video_args);
            
        }else if( isset($request['category']) && $request['category'] != null ) {
            $video_args = [
                "post_type" => "video",
                "posts_per_page" => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'tax-video',
                        'field' => 'slug',
                        'terms' => [$request['category']]
                    ]
                ]
            ];
    
            return __get_sanitize_videos( Timber::get_posts($video_args) );  
    
        }else {
            $video_categories = Timber::get_terms([
                "taxonomy" => "tax-video",
                'meta_key'  => 'order',
                'orderby'   => 'meta_value_num',
                'order' => 'ASC'                
            ]);

            $videos = [];

            foreach($video_categories as $category){
                $video_args = [
                    "post_type" => "video",
                    "posts_per_page" => -1,
                    'tax_query' => [
                        [
                            'taxonomy' => 'tax-video',
                            'field' => 'slug',
                            'terms' => [$category->name]
                        ]
                    ]
                ];

                $videos = array_merge($videos, Timber::get_posts($video_args));
            }

            return __get_sanitize_videos($videos);  
        }
    }
    
    //2. Likes ---------------------------------------//
    public static function getLikes($request){
        return __getLikes($request);
    }

    public static function updateLikes($request){
        $likes_count = get_post_meta($request['post_id'], 'post_likes_count');
    
        if( $likes_count ){
            try {
                return __updateLikes($request, 'video', $likes_count);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }else{
            throw new Exception('No defined likes');
        }        
    }

    public static function checkoutUserLike($request){
        return __checkoutUserLike($request, 'video');        
    }

    //3. Comments ---------------------------------------//
    public static function getComments($request){
        return __getComments($request);
    }

    public static function addComment($request){
        return __addComment($request);
    }
    
    public static function addAnswer($request){
        return __addAnswer($request);
    }
}
