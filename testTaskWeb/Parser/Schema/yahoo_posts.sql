create table yahoo_posts
(
    id int auto_increment,
    title varchar(255) null,
    description text null,
    link varchar(255) null,
    pub_date varchar(255) null,
    source varchar(255) null,
    guid varchar(255) null,
    category varchar(255) null,
    media_content text null,
    media_credit text null,
    media_text text null,
    published smallint(1) DEFAULT 0 null,
    constraint yahoo_posts_pk
        primary key (id)
);