<?php

/*
 *  This file is part of dotSlick, a plugin for Dotclear 2.
 *  
 *  Copyright (c) 2019 Bruno Avet
 *  Licensed under the GPL version 2.0 license.
 *  A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Description of class
 *
 * @author bruno
 */
class dsGalleries {

//put your code here

    public static function get($id = null, $callback = null,$details = false) {
        global $core;
        if (is_null($id)) {
            $id = [];
        } elseif (is_array($id)) {
            foreach ($id as $k => $i) {
                $id[$k] = (integer) $i;
            }
            $id = ['post_id' => $id];
        } else {
            $id = ['post_id' => (integer) $id];
        }

        $params = array_merge(["where" => " AND (post_excerpt LIKE '%::dotslick%' or post_content LIKE '%::dotslick%')",
            "post_type" => explode(" ", $core->blog->settings->dotslick->post_types)
                ], $id);

        $ret = [];
        $rs = $core->blog->getPosts($params);
        while ($rs->fetch()) {
            $gallery = ["id" => $rs->post_id,
                "type" => $rs->post_type,
                "title" => $rs->post_title,
                "url" => $core->blog->url . $core->getPostPublicURL($rs->post_type, $rs->post_url),
                "adminurl" => $core->getPostAdminURL($rs->post_type, $rs->post_id),
                "excerpt" => $rs->post_excerpt,
                "content" => $rs->post_content,
                "count" => preg_match_all("/::dotslick/m", $rs->post_content . $rs->post_excerpt)
            ];
            if ($callback !== null) {
                $ret[] = call_user_func_array($callback, $gallery);
            } else {
                $ret[] = $gallery;
            }
            
        }
        
        if($details){
            foreach($gallery as $g){
                $mdesc =[];
                preg_match_all("/::dotslick([^:]*)::/",$g["excerpt"].$g["content"]);
                $g["galleries"]=[];
                foreach($mdesc[1] as $desc){
//do something with desc
                }
            }
        }
        
        return $ret;
    }

    public static function regenerate($id,&$count = null) {
        global $core;

        try {
            $res = $core->blog->getPosts(['post_id' => $id, "post_type" => explode(" ", $core->blog->settings->dotslick->post_types)]);
            $res->fetch();

            $cur = $core->con->openCursor($core->prefix . 'post');
            $cur->post_title = $res->post_title;
            $cur->cat_id = ($res->cat_id ?: null);
            $cur->post_dt = $res->post_dt ? date('Y-m-d H:i:00', strtotime($res->post_dt)) : '';
            $cur->post_format = $res->post_format;
            $cur->post_password = $res->post_password;
            $cur->post_lang = $res->post_lang;
            $cur->post_title = $res->post_title;
            $cur->post_excerpt = $res->post_excerpt;
            $cur->post_excerpt_xhtml = $res->post_excerpt_xhtml;
            $cur->post_content = $res->post_content;
            $cur->post_content_xhtml = $res->post_content_xhtml;
            $cur->post_notes = $res->post_notes;
            $cur->post_status = $res->post_status;
            $cur->post_selected = (integer) $res->post_selected;
            $cur->post_open_comment = (integer) $res->post_open_comment;
            $cur->post_open_tb = (integer) $res->post_open_tb;

            if($count !== null){
                $count = preg_match_all("/::dotslick/m", $res->post_content . $res->post_excerpt);
            }
            $core->blog->updPost($id, $cur);
        } catch (Exception $e) {
            $core->error->add(__METHOD__ .':'. sprintf(__("problem with gallery %d regeneration."),$id)."\nError message is :".$e->getMessage() );
            return false;
        }
        return true;
    }
}
