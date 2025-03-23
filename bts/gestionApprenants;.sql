create database gestionApprenants;
use gestionApprenants;
create table Formateur(matricule varchar(20) primary Key , nomForm varchar(30) not null, prenomForm varchar(30) not null, email varchar(20), telephone varchar(20), specialite varchar(20) not null) ;
use gestionApprenants;
create table Apprenant(code varchar(20)  primary key , nomAp varchar(30) not null, prenomAp varchar(30) not null, email varchar(30), telephone varchar(20), dateIns varchar(20));
use gestionApprenants;
create table Periode(idP varchar(20)  primary key , idAp varchar(20), titre varchar(20), dateDebut varchar(20), dateFin varchar(20), foreign key(idAp) references Apprenant(code));
use gestionApprenants;
create table EmploiTemps(idE int auto_increment primary key, heureDebut varchar(15), heureFin varchar(15), jour varchar(30) );
use gestionApprenants;
create table Cours(idC varchar(20) primary key, idForm varchar(20), idE int, titreC varchar(20) not null, description varchar(35), dateD varchar(20), dateF varchar(20), foreign key(idForm) references Formateur(matricule), foreign key(idE) references EmploiTemps(idE));
use gestionApprenants;
CREATE TABLE suivieCours (
    idAp VARCHAR(20), 
    idC VARCHAR(20), 
    present BOOLEAN, 
    date DATE,  
    FOREIGN KEY(idAp) REFERENCES Apprenant(code), 
    FOREIGN KEY(idC) REFERENCES Cours(idC), 
    PRIMARY KEY(idAp, idC, date)  
);
use gestionApprenants;
create table Comptabilite(numero int auto_increment primary key,idAp varchar(20), montant int, typeTrans varchar(20), foreign key(idAp) references Apprenant(code));
use gestionApprenants;
create table FormationDistante(idFormat varchar(20) primary key, idAp varchar(20), titreF varchar(20), url varchar(50), description varchar(100), foreign key(idAp) references Apprenant(code));

