create table evrak_tipleri
(
    id       int auto_increment
        primary key,
    isim     varchar(250)                          null,
    aciklama varchar(1000)                         null,
    grup     varchar(150)                          null,
    created  timestamp default current_timestamp() null,
    constraint evrak_tipleri_evrak_isim_uindex
        unique (isim)
)
    charset = utf8;

INSERT INTO evrak_tipleri (id, isim, aciklama, grup, created) VALUES (1, 'Yeni evrak 2', null, 'Personaldaten', '2022-04-05 13:28:24');
INSERT INTO evrak_tipleri (id, isim, aciklama, grup, created) VALUES (2, 'test', 'test', 'Personaldaten', '2022-04-05 16:51:21');