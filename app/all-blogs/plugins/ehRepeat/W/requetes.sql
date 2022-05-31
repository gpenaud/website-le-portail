Sélectionne tout

SELECT P.post_id, post_url, post_title, event_startdt, event_enddt, R.rpt_freq, R.rpt_id, R.rpt_evt, post_type FROM  dcgamv3_post P INNER JOIN dcgamv3_user U ON U.user_id = P.user_id LEFT OUTER JOIN dcgamv3_category C ON P.cat_id = C.cat_id INNER JOIN dcgamv3_eventhandler EH ON  EH.post_id = P.post_id LEFT OUTER JOIN dcgamv3_ehrepeat R ON EH.post_id = R.rpt_evt WHERE P.blog_id = 'gam' AND ((post_status = 1 AND post_password IS NULL ) ) AND post_type  IN ('eventhandler','ehrepeat') AND EH.event_enddt > TIMESTAMP '2019-02-23 04:35:15'  ORDER BY event_startdt ASC

Selectionne tous les rpt_id,rpt_evt,rpt_freq pour les post_type='eventhandler'|'ehrepeat'
select E.rpt_id,E.rpt_evt,E.rpt_freq from dcgamv3_ehrepeat E inner join dcgamv3_ehrepeat_auto A on E.rpt_id = A.rpt_id 


Sélectionne tous les slaves pour master_id :

SELECT P.post_id, P.blog_id, P.user_id, P.cat_id, post_dt, post_tz, post_creadt, post_upddt, post_format, post_password, post_url, post_lang, post_title, post_excerpt, post_excerpt_xhtml, post_content, post_content_xhtml, post_notes, event_startdt, event_enddt, event_address, event_latitude, event_longitude, event_zoom, R.rpt_freq, R.rpt_id, R.rpt_evt, post_type, post_meta, post_status, post_firstpub, post_selected, post_position, post_open_comment, post_open_tb, nb_comment, nb_trackback, U.user_name, U.user_firstname, U.user_displayname, U.user_email, U.user_url, C.cat_title, C.cat_url, C.cat_desc FROM dcgamv3_post P INNER JOIN dcgamv3_user U ON U.user_id = P.user_id LEFT OUTER JOIN dcgamv3_category C ON P.cat_id = C.cat_id INNER JOIN dcgamv3_eventhandler EH ON  EH.post_id = P.post_id LEFT OUTER JOIN dcgamv3_ehrepeat_auto A ON EH.post_id = A.rpt_evt LEFT OUTER JOIN dcgamv3_ehrepeat R ON A.rpt_id = R.rpt_id WHERE P.blog_id = 'gam' AND ((post_status = 1 AND post_password IS NULL ) ) AND post_type  IN ('ehrepeat') AND EH.event_enddt > TIMESTAMP '2019-02-26 22:32:31'  AND R.rpt_evt = $master_id ORDER BY event_startdt ASC 


Sélectionne tous les événements et slaves :
SELECT P.post_id, post_title, event_startdt, event_enddt, T.rpt_freq, T.rpt_id, T.rpt_evt, post_type FROM dcgamv3_post P  INNER JOIN dcgamv3_eventhandler EH ON  EH.post_id = P.post_id LEFT OUTER JOIN (SELECT A.rpt_evt, A.rpt_id, R.rpt_evt as rpt_master, R.rpt_freq from dcgamv3_ehrepeat_auto A LEFT OUTER JOIN dcgamv3_ehrepeat R on R.rpt_id = A.rpt_id UNION SELECT rpt_evt, rpt_id, rpt_evt as rpt_master, rpt_freq from dcgamv3_ehrepeat) as T ON T.rpt_evt = P.post_id WHERE P.blog_id = 'gam' AND ((post_status = 1 AND post_password IS NULL ) ) AND post_type  IN ('eventhandler','ehrepeat') AND EH.event_enddt > TIMESTAMP '2019-02-26 22:35:42'  
ORDER BY `P`.`post_id` ASC

Sélectionne tous les posts qui ont le même rpt_id que $post_id
SELECT P.post_id, post_title,EH.event_startdt,R.rpt_freq FROM dcgamv3_post P LEFT OUTER JOIN dcgamv3_eventhandler EH using(post_id) LEFT OUTER JOIN dcgamv3_ehrepeat_auto A ON P.post_id = A.rpt_evt LEFT OUTER JOIN dcgamv3_ehrepeat R using(rpt_id) WHERE P.blog_id = 'gam' AND ((post_status = 1 AND post_password IS NULL ) ) AND post_type  IN ('ehrepeat','eventhandler') AND R.rpt_id in (SELECT A.rpt_id from dcgamv3_ehrepeat_auto A inner join dcgamv3_ehrepeat_auto B using(rpt_id) where B.rpt_evt IN ($post_id)) ORDER BY post_dt DESC

sélectionne tous les rpt_id / rpt_evt de dcgamv3_ehrepeat & dcgamv3_ehrepeat_auto
select rpt_id,rpt_evt from dcgamv3_ehrepeat_auto union select rpt_id,rpt_evt from dcgamv3_ehrepeat

sélectionne tous les rpt_id / rpt_evt de dcgamv3_ehrepeat & dcgamv3_ehrepeat_auto ayant le même rpt_id que post_id
select A.rpt_evt from (select rpt_id,rpt_evt from dcgamv3_ehrepeat_auto union select rpt_id,rpt_evt from dcgamv3_ehrepeat) A inner join (select rpt_id,rpt_evt from dcgamv3_ehrepeat_auto union select rpt_id,rpt_evt from dcgamv3_ehrepeat) B using(rpt_id) where B.rpt_evt in ($post_id)

sélectionne tous les événements ayant le même rpt_id que post_id
SELECT P.post_id, post_title,EH.event_startdt, R.rpt_freq FROM dcgamv3_post P LEFT OUTER JOIN dcgamv3_eventhandler EH using(post_id) left outer join (select rpt_id,rpt_evt from dcgamv3_ehrepeat_auto union select rpt_id,rpt_evt from dcgamv3_ehrepeat) A on P.post_id = A.rpt_evt inner join (select rpt_id,rpt_evt from dcgamv3_ehrepeat_auto union select rpt_id,rpt_evt from dcgamv3_ehrepeat) B on A.rpt_id = B.rpt_id  left outer join dcgamv3_ehrepeat R on R.rpt_id = B.rpt_id WHERE P.blog_id = 'gam' AND ((post_status = 1 AND post_password IS NULL ) ) AND post_type  IN ('ehrepeat','eventhandler') and B.rpt_evt in ($post_id) ORDER BY post_dt DESC

