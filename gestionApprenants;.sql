create database gestionApprenant;
use gestionApprenant;
create table Formateur(matricule varchar(20) primary Key , nomForm varchar(30) not null, prenomForm varchar(30) not null, email varchar(20), telephone varchar(20), specialite varchar(20) not null,login varchar(20) not null) ;
use gestionApprenant;
create table Apprenant(code varchar(20)  primary key , nomAp varchar(30) not null, prenomAp varchar(30) not null, email varchar(30), telephone varchar(20), dateIns varchar(20),login varchar(20));
use gestionApprenant;
create table EmploiTemps(idE int auto_increment primary key, heureDebut varchar(15), heureFin varchar(15), jour varchar(30) );
use gestionApprenant;
create table Cours(idC varchar(20) primary key, idForm varchar(20),  titreC varchar(20) not null, description varchar(35),dateD varchar(35),dateF varchar(35), foreign key(idForm) references Formateur(matricule));
use gestionApprenant;
CREATE TABLE suivieCours (
    idAp VARCHAR(20), 
    idC VARCHAR(20), 
    present BOOLEAN
    , date varchar(20), 
    FOREIGN KEY(idAp) REFERENCES Apprenant(code), 
    FOREIGN KEY(idC) REFERENCES Cours(idC), 
    PRIMARY KEY(idAp, idC, date)  
);
use gestionApprenant;
create table inscription(numero int auto_increment primary key,idAp varchar(20), montant int, foreign key(idAp) references Apprenant(code));

use gestionApprenant;
create table administrateur(id varchar(20) primary key, idE int, login varchar(20),nomAd varchar(20),prenomAd varchar(20),foreign key(idE) references EmploiTemps(idE));

