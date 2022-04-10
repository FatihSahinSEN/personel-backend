create table meslekler
(
    id       int auto_increment
        primary key,
    meslek   varchar(200)                          null,
    aciklama varchar(2000)                         null,
    created  timestamp default current_timestamp() null
)
    charset = utf8;

INSERT INTO meslekler (id, meslek, aciklama, created) VALUES (1, 'asdasd', '123123', '2022-04-04 22:59:22');
INSERT INTO meslekler (id, meslek, aciklama, created) VALUES (2, 'muhase e', null, '2022-04-04 23:01:09');
INSERT INTO meslekler (id, meslek, aciklama, created) VALUES (3, 'Developer', null, '2022-04-06 13:27:13');