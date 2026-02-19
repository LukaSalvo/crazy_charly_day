CREATE
    EXTENSION IF NOT EXISTS pgcrypto;

DROP TABLE IF EXISTS categorie CASCADE;
create table categorie
(
    id      INTEGER PRIMARY KEY,
    libelle VARCHAR
);

DROP TABLE IF EXISTS age CASCADE;
create table age
(
    id      INTEGER PRIMARY KEY,
    libelle VARCHAR
);

DROP TABLE IF EXISTS etat CASCADE;
create table etat
(
    id      INTEGER PRIMARY KEY,
    libelle VARCHAR
);

DROP TABLE IF EXISTS article CASCADE;
create table article
(
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    designation VARCHAR,
    id_categ    INTEGER,
    id_age      INTEGER,
    id_etat     INTEGER,
    prix        NUMERIC(10, 2),
    poids       NUMERIC(10, 3),

    CONSTRAINT fk_categ
        FOREIGN KEY (id_categ)
            REFERENCES categorie (id),
    CONSTRAINT fk_age
        FOREIGN KEY (id_age)
            REFERENCES age (id),
    CONSTRAINT fk_etat
        FOREIGN KEY (id_etat)
            REFERENCES etat (id)
);

DROP TABLE IF EXISTS utilisateur CASCADE;
create table utilisateur
(
    id    UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom   VARCHAR,
    mail  VARCHAR,
    mdp   VARCHAR,
    admin BOOLEAN
);

DROP TABLE IF EXISTS client CASCADE;
create table client
(
    id      UUID PRIMARY KEY,
    age     INTEGER,
    categ_1 INTEGER,
    categ_2 INTEGER,
    categ_3 INTEGER,
    categ_4 INTEGER,
    categ_5 INTEGER,
    categ_6 INTEGER,

    CONSTRAINT fk_id
        FOREIGN KEY (id)
            REFERENCES utilisateur (id),
    CONSTRAINT fk_categ1
        FOREIGN KEY (categ_1)
            REFERENCES categorie (id),
    CONSTRAINT fk_categ2
        FOREIGN KEY (categ_2)
            REFERENCES categorie (id),
    CONSTRAINT fk_categ3
        FOREIGN KEY (categ_3)
            REFERENCES categorie (id),
    CONSTRAINT fk_categ4
        FOREIGN KEY (categ_4)
            REFERENCES categorie (id),
    CONSTRAINT fk_categ5
        FOREIGN KEY (categ_5)
            REFERENCES categorie (id),
    CONSTRAINT fk_categ6
        FOREIGN KEY (categ_6)
            REFERENCES categorie (id)
);

DROP TABLE IF EXISTS box CASCADE;
create table box
(
    id        UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    id_client UUID,
    prix      NUMERIC(10, 2),
    poids     NUMERIC(10, 3),
    score     INTEGER,
    valide    BOOLEAN DEFAULT FALSE,

    CONSTRAINT fk_id_client
        FOREIGN KEY (id_client)
            REFERENCES client (id)
                ON DELETE CASCADE
);

DROP TABLE IF EXISTS campagne CASCADE;
create table campagne
(
    id        UUID PRIMARY KEY,
    poids_max NUMERIC(10, 3),
    prix_min  NUMERIC(10, 2),
    prix_max  NUMERIC(10, 2)
);

DROP TABLE IF EXISTS boxobj CASCADE;
create table boxobj
(
    id_box     UUID,
    id_article UUID,

    CONSTRAINT fk_box
        FOREIGN KEY (id_box)
            REFERENCES box (id)
                ON DELETE CASCADE,
    CONSTRAINT fk_article
        FOREIGN KEY (id_article)
            REFERENCES article (id)
);

DROP TABLE IF EXISTS boxcampagne CASCADE;
create table boxcampagne
(
    id_box      UUID,
    id_campagne UUID,

    CONSTRAINT fk_box
        FOREIGN KEY (id_box)
            REFERENCES box (id)
                ON DELETE CASCADE,
    CONSTRAINT fk_campagne
        FOREIGN KEY (id_campagne)
            REFERENCES campagne (id)
);

INSERT INTO categorie (id, libelle)
VALUES
(1, 'SOC'),
(2, 'FIG'),
(3, 'CON'),
(4, 'EXT'),
(5, 'EVL'),
(6, 'LIV');

INSERT INTO age (id, libelle)
VALUES
    (1, 'BB'),
    (2, 'PE'),
    (3, 'EN'),
    (4, 'AD');

INSERT INTO etat (id, libelle)
VALUES
    (1, 'N'),
    (2, 'TB'),
    (3, 'B');

INSERT INTO utilisateur (nom, mail, mdp, admin)
VALUES
    ('Alice Dupont', 'alice@example.com', '$2y$10$vt7r/OBs4SpdcO4unDyXpOLUgi9m4x0sAf41I6vRa4plPsysxyEEu', TRUE),
    ('Bob Martin', 'bob@example.com', '$2y$10$9YIy6LPlN7VhjTyupXqhyO5bD8245vWwfvgqIl0rNj9DLWQ76Blm2', FALSE),
    ('Charlie Bernard', 'charlie@example.com', '$2y$10$Ui8eGzKeumlu6H3ttdgFwuqdSTtaEWmbnhz.c007FKWWFBqznIju2', FALSE);

INSERT INTO client (id, age, categ_1, categ_2, categ_3, categ_4, categ_5, categ_6)
VALUES
    ((SELECT id FROM utilisateur WHERE nom='Alice Dupont'), 4, 1, 2, NULL, NULL, NULL, NULL),
    ((SELECT id FROM utilisateur WHERE nom='Bob Martin'), 3, 3, 4, 5, NULL, NULL, NULL),
    ((SELECT id FROM utilisateur WHERE nom='Charlie Bernard'), 2, 6, NULL, NULL, NULL, NULL, NULL);

INSERT INTO article (designation, id_categ, id_age, id_etat, prix, poids)
VALUES
    ('Puzzle 1000 pcs', 2, 3, 1, 25.50, 1.2),
    ('Livre enfant', 3, 3, 2, 15.00, 0.5),
    ('Doudou', 1, 1, 1, 12.00, 0.3),
    ('Jeu éducatif', 2, 2, 3, 30.00, 1.0),
    ('Casque vélo', 4, 4, 2, 45.50, 0.8);

INSERT INTO box (id_client, prix, poids, score, valide)
VALUES
    ((SELECT id FROM utilisateur WHERE nom = 'Bob Martin'), 80.00, 3.0, 90, FALSE),
    ((SELECT id FROM utilisateur WHERE nom = 'Charlie Bernard'), 100.50, 4.2, 85, FALSE);

INSERT INTO campagne (id, poids_max, prix_min, prix_max)
VALUES
    (gen_random_uuid(), 5.0, 50.0, 100.0),
    (gen_random_uuid(), 3.0, 30.0, 70.0);

INSERT INTO boxobj (id_box, id_article)
VALUES
    ((SELECT id FROM box LIMIT 1), (SELECT id FROM article WHERE designation='Puzzle 1000 pcs')),
    ((SELECT id FROM box LIMIT 1), (SELECT id FROM article WHERE designation='Livre enfant')),
    ((SELECT id FROM box OFFSET 1 LIMIT 1), (SELECT id FROM article WHERE designation='Doudou'));

INSERT INTO boxcampagne (id_box, id_campagne)
VALUES
    ((SELECT id FROM box LIMIT 1), (SELECT id FROM campagne LIMIT 1)),
    ((SELECT id FROM box OFFSET 1 LIMIT 1), (SELECT id FROM campagne OFFSET 1 LIMIT 1));
