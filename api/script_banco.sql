CREATE DATABASE testeNewm;
Use testeNewm;

CREATE TABLE addresses(
	id INT PRIMARY KEY AUTO_INCREMENT,
    cep INT NOT NULL,
    street VARCHAR(60) NOT NULL,
    number VARCHAR(10) NOT NULL,
    neighborhood VARCHAR(60) NOT NULL,
    complement VARCHAR(100) NOT NULL,
    city VARCHAR(35) NOT NULL,
    state VARCHAR(2) NOT NULL
);

CREATE TABLE clients(
	id INT PRIMARY KEY AUTO_INCREMENT,
    addressId INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    birthDate DATE NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    phone VARCHAR(11) NOT NULL,
    email VARCHAR(100) NOT NULL,
    observation VARCHAR(200),
    CONSTRAINT FK_Clients_Addresses FOREIGN KEY (addressId) REFERENCES addresses(id)
);

/* Seguindo a lógica de nomenclatura da constante: FK_QuemRecebeFK_DeQuemEFK */

/* Operações para a tabela clients */

SELECT * FROM clients;

SELECT * FROM clients WHERE id = 1;

INSERT INTO clients(addressId,name,birthDate,cpf,phone,email,observation) VALUES(1,"","","","","","");

UPDATE clients SET name = "", birthDate = "", cpf = "", phone = "", email = "", address = "", observation = "" WHERE id = 1;

DELETE FROM clients WHERE id = 1;

/* Operações para a tabela addresses */

SELECT * FROM addresses;

SELECT * FROM addresses WHERE id = 1;

INSERT INTO addresses(cep,street,number,neighborhood,complement,city,state) VALUES(123,"","","","","","");

UPDATE addresses SET cep = 123, street = "", number = "", neighborhood = "", complement = "", city = "", state = "" WHERE id = 1;

DELETE FROM addresses WHERE id = 1;

/* Inserções para teste */
INSERT INTO addresses(cep,street,number,neighborhood,complement,city,state) VALUES(15750000,"Rua Cohab II","12345","Bairro Adescau","Casa","Santa Albertina","SP");
INSERT INTO clients(addressId,name,birthDate,cpf,phone,email,observation) VALUES(1,"Bruno Noveli","1999-04-21","47289470842","17997432642","brunozuppwolf@gmail.com","Observando alguma coisa");

/* Juntando as tabelas */

SELECT C.*, A.* FROM clients C
INNER JOIN addresses A ON A.id = c.addressId;
