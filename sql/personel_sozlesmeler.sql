create table sozlesmeler
(
    id             int auto_increment
        primary key,
    firma_id       int                                   not null,
    personel_id    int                                   not null,
    baslama_tarihi date                                  not null,
    ucret_grubu    int                                   not null,
    saat_ucreti    float                                 not null,
    ek_ucret       float                                 not null,
    imza_tarihi    date                                  not null,
    created        timestamp default current_timestamp() null
)
    charset = utf8;

INSERT INTO sozlesmeler (id, firma_id, personel_id, baslama_tarihi, ucret_grubu, saat_ucreti, ek_ucret, imza_tarihi, created) VALUES (1, 1, 1, '2022-04-06', 1, 15, 12.2, '2022-04-07', '2022-04-06 16:09:01');
INSERT INTO sozlesmeler (id, firma_id, personel_id, baslama_tarihi, ucret_grubu, saat_ucreti, ek_ucret, imza_tarihi, created) VALUES (2, 1, 2, '2022-01-01', 1, 12.15, 14.15, '2022-04-06', '2022-04-06 17:42:57');
INSERT INTO sozlesmeler (id, firma_id, personel_id, baslama_tarihi, ucret_grubu, saat_ucreti, ek_ucret, imza_tarihi, created) VALUES (3, 1, 1, '2000-01-01', 1, 13.15, 13.16, '2000-01-01', '2022-04-06 17:45:14');
INSERT INTO sozlesmeler (id, firma_id, personel_id, baslama_tarihi, ucret_grubu, saat_ucreti, ek_ucret, imza_tarihi, created) VALUES (4, 1, 2, '2000-02-01', 1, 20.05, 0, '2000-01-01', '2022-04-06 17:47:03');