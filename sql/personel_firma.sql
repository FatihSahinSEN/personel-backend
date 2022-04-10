create table firma
(
    id              int auto_increment
        primary key,
    adi             varchar(150) not null,
    cadde           varchar(100) not null,
    postakodu_sehir varchar(100) not null
)
    charset = utf8;

INSERT INTO firma (id, adi, cadde, postakodu_sehir) VALUES (1, 'BIENE GmbH', 'Marconistr.30', '50769 Köln ');