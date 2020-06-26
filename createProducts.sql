use mollinger;

create table Products(
    ID int not null AUTO_INCREMENT,
    name varchar(50),
    description varchar(500),
    Price int(10),
    Picture varchar(50),
    Primary key (ID)
);