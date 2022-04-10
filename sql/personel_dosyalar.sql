create table dosyalar
(
    id             int auto_increment
        primary key,
    dosya          varchar(300)                          null,
    dosya_adi      varchar(300)                          not null,
    evrak_tip_id   int                                   not null,
    dosya_boyutu   int                                   null,
    dosya_uzantisi varchar(10)                           null,
    dosya_yolu     text                                  null,
    personel_no    int                                   not null,
    bitis_tarihi   date                                  null,
    status         int                                   null,
    created        timestamp default current_timestamp() null
)
    charset = utf8;

INSERT INTO dosyalar (id, dosya, dosya_adi, evrak_tip_id, dosya_boyutu, dosya_uzantisi, dosya_yolu, personel_no, bitis_tarihi, status, created) VALUES (5, 'LpfsBXLqJfq_mpZmgAZiIwA_1649242629.png', '4753032_bulb_electric_led_light_luminaire_icon.png', 1, 440, 'png', 'C:\\xampp7.3.31\\htdocs\\personal\\personel-backend\\public\\upload\\155\\', 155, '2023-01-01', 1, '2022-04-06 13:57:09');