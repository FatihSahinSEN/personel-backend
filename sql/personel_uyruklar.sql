create table uyruklar
(
    id      int auto_increment
        primary key,
    uyruk   varchar(150)                          null,
    created timestamp default current_timestamp() null
)
    charset = utf8;

INSERT INTO uyruklar (id, uyruk, created) VALUES (1, 'asdasd', '2022-04-04 22:59:30');
INSERT INTO uyruklar (id, uyruk, created) VALUES (2, 'Türk', '2022-04-06 13:27:20');