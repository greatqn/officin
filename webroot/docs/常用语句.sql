show tables;
use intouch;

isns_admin;
isns_album;
isns_album_comment;
isns_ask;
isns_ask_reply;
isns_ask_type;
isns_backgroup;
isns_blog;
isns_blog_comment;
isns_blog_sort;
isns_business_card;
isns_collect;
isns_day_remark;
isns_event;
isns_event_comment;
isns_event_invite;
isns_event_members;
isns_event_photo;
isns_event_type;
isns_frontgroup;
isns_group_members;
isns_group_subject;
isns_group_subject_comment;
isns_group_type;
isns_groups;
isns_guest;
isns_hi;
isns_integral;
isns_invite_code;
isns_mood;
isns_mood_comment;
isns_msg_inbox;
isns_msg_outbox;
isns_msgboard;
isns_online;
isns_pals_def_sort;
isns_pals_mine;
isns_pals_request;
isns_pals_sort;
isns_photo;
isns_photo_comment;
isns_plugin_url;
isns_plugins;
isns_poll;
isns_poll_comment;
isns_polloption;
isns_polluser;
isns_recent_affair;
isns_recommend;
isns_remind;
isns_report;
isns_share;
isns_share_comment;
isns_tag;
isns_tag_relation;
isns_template_card;
isns_tmp_file;
isns_uploadfile;
isns_user_activation;
isns_user_info;
isns_user_information;
isns_users;

//名片
select * from isns_business_card limit 0,10

select * from isns_pals_mine where user_id=13 and accepted > 0 

//用户
select * from isns_users limit 0,10;
update isns_users
set user_pws ='96e79218965eb72c92a549dd5a330112'
where user_id = 5;

//提醒
select * from isns_remind limit 0,10;

select * from isns_remind where user_id in (5,13)

select * from isns_remind where user_id in (33,13)

type_id 3:好友申请,5:留言

//对话,站内信
isns_msg_inbox;
isns_msg_outbox;
isns_msgboard;

select * from isns_msg_inbox limit 0,10;
select * from isns_msg_outbox limit 0,10;
select * from isns_msgboard limit 0,10;

select pals_id,pals_name,pals_ico,pals_sort_id,pals_sort_name,cn_name from isns_pals_mine where  user_id = 29 order by  pals_sort_id  desc   limit 0,20

select * from isns_users  where user_id > 20 limit 0,10

select * from isns_pals_mine 
order by id desc
limit  0,10

update isns_pals_mine
set cn_name = null
where id = 76


insert into isns_remind (user_id,type_id,date,content,    is_focus,from_uid,from_uname,from_uico,link) 
values (32,0,'2012-11-25 22:40:12','姓名的名片已添加进您的名片夹',1,     ,'','','32') 

