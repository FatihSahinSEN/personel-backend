create table kullanicilar
(
    id            int auto_increment
        primary key,
    kullanici_adi varchar(200)                          null,
    sifre         varchar(250)                          not null,
    soyisim       varchar(100)                          null,
    yetki         int       default 1                   null,
    fotograf      varchar(1000)                         null,
    isim          varchar(100)                          null,
    created       timestamp default current_timestamp() null,
    status        int       default 1                   null,
    constraint kullanicilar_kullanici_adi_uindex
        unique (kullanici_adi)
)
    charset = utf8;

INSERT INTO kullanicilar (id, kullanici_adi, sifre, soyisim, yetki, fotograf, isim, created, status) VALUES (13, 'fatihsen7', '2ed0184d7fb2c7c1c941d751158d74744c5a4565', 'ŞEN', 2, null, 'Fatih', '2022-03-25 12:21:31', 1);
INSERT INTO kullanicilar (id, kullanici_adi, sifre, soyisim, yetki, fotograf, isim, created, status) VALUES (14, 'nevzat', '2ed0184d7fb2c7c1c941d751158d74744c5a4565', 'KOCAS', 2, null, 'NEVZAT', '2022-04-04 22:29:09', 1);
INSERT INTO kullanicilar (id, kullanici_adi, sifre, soyisim, yetki, fotograf, isim, created, status) VALUES (15, 'ddd', '966924b9d1fe64a3e5e056ed1677fde3b62225e5', 'asdasasd', 1, null, 'asdasd', '2022-04-04 23:00:07', 0);