create table sigorta_sirketleri
(
    id      int auto_increment
        primary key,
    isim    varchar(300)                          null,
    created timestamp default current_timestamp() null,
    constraint sigorta_sirketleri_isim_uindex
        unique (isim)
)
    charset = utf8;

INSERT INTO sigorta_sirketleri (id, isim, created) VALUES (1, 'test', '2022-04-05 15:48:55');
INSERT INTO sigorta_sirketleri (id, isim, created) VALUES (2, 'merhaba', '2022-04-05 15:50:55');
INSERT INTO sigorta_sirketleri (id, isim, created) VALUES (3, 'AvivaSA', '2022-04-06 13:27:57');