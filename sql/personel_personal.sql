create table personal
(
    id                         int auto_increment
        primary key,
    personel_no                int                                   null,
    isim                       varchar(100)                          null,
    soyisim                    varchar(100)                          null,
    dogum_tarihi               date                                  null,
    dogum_yeri                 varchar(100)                          null,
    posta_kodu_id              int                                   null,
    ulke_id                    int                                   not null,
    cadde                      varchar(200)                          null,
    meslek_id                  int                                   null,
    ise_giris_tarihi           date                                  null,
    sigorta_sirketi_id         int                                   null,
    kimlik_no                  varchar(100)                          null,
    sosyal_guvenlik_no         varchar(100)                          null,
    uyruk_id                   int                                   null,
    telefon                    varchar(100)                          null,
    kimlik_seri_no             varchar(100)                          null,
    kimlik_gecerlilik_tarihi   date                                  null,
    pasaport_no                varchar(15)                           null,
    pasaport_gecerlilik_tarihi date                                  null,
    oturum_izin_no             varchar(100)                          null,
    oturum_izin_tarihi         date                                  null,
    eposta                     varchar(100)                          null,
    guvenlik_belgesi           varchar(100)                          null,
    created                    timestamp default current_timestamp() null,
    constraint personal_personel_no_uindex
        unique (personel_no)
)
    charset = utf8;

create index personal_meslekler_id_fk
    on personal (meslek_id);

create index personal_posta_kodlari_id_fk
    on personal (posta_kodu_id);

create index personal_sigorta_sirketleri_id_fk
    on personal (sigorta_sirketi_id);

create index personal_ulkeler_code_fk
    on personal (ulke_id);

create index personal_uyruklar_id_fk
    on personal (uyruk_id);

INSERT INTO personal (id, personel_no, isim, soyisim, dogum_tarihi, dogum_yeri, posta_kodu_id, ulke_id, cadde, meslek_id, ise_giris_tarihi, sigorta_sirketi_id, kimlik_no, sosyal_guvenlik_no, uyruk_id, telefon, kimlik_seri_no, kimlik_gecerlilik_tarihi, pasaport_no, pasaport_gecerlilik_tarihi, oturum_izin_no, oturum_izin_tarihi, eposta, guvenlik_belgesi, created) VALUES (1, 155, 'ahmet', 'kayaalp', '1978-01-15', 'bursa', 0, 0, '', 2, '2000-01-01', 0, '', '', 1, '1515', '', null, '', null, '', null, 'aaa@sadsada.de', '', '2022-04-04 23:01:25');
INSERT INTO personal (id, personel_no, isim, soyisim, dogum_tarihi, dogum_yeri, posta_kodu_id, ulke_id, cadde, meslek_id, ise_giris_tarihi, sigorta_sirketi_id, kimlik_no, sosyal_guvenlik_no, uyruk_id, telefon, kimlik_seri_no, kimlik_gecerlilik_tarihi, pasaport_no, pasaport_gecerlilik_tarihi, oturum_izin_no, oturum_izin_tarihi, eposta, guvenlik_belgesi, created) VALUES (2, 777, 'FATİH', 'ŞEN', '1980-01-01', 'İSTANBUL', 13738, 218, 'Vatan', 3, '2000-01-01', 3, '1111111111', '99999999999', 2, '05358564253', '968574', '2023-01-01', 'AA-65984', '2023-01-01', '616164', '2023-01-01', 'fatihsahinsen@outlook.com', '6666666', '2022-04-06 13:29:05');