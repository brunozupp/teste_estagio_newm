CREATE DATABASE testeNewm;
Use testeNewm;

CREATE TABLE clients(
	id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    birthDate DATE NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    phone VARCHAR(11) NOT NULL,
    email VARCHAR(100) NOT NULL,
    observation VARCHAR(300)
);

/* Seguindo a lógica de nomenclatura da constante: FK_QuemRecebeFK_DeQuemEFK */
CREATE TABLE addresses(
	id INT PRIMARY KEY AUTO_INCREMENT,
    clientId INT UNIQUE,
    cep INT NOT NULL,
    street VARCHAR(60) NOT NULL,
    number VARCHAR(10) NOT NULL,
    neighborhood VARCHAR(60) NOT NULL,
    complement VARCHAR(100) NOT NULL,
    city VARCHAR(35) NOT NULL,
    state VARCHAR(2) NOT NULL,
    CONSTRAINT FK_Addresses_Clients FOREIGN KEY (clientId) REFERENCES clients(id) ON DELETE CASCADE
);

DROP TABLE clients;
DROP TABLE addresses;

/* Operações para a tabela clients */

SELECT * FROM clients;

SELECT * FROM clients WHERE id = 1;

INSERT INTO clients(name,birthDate,cpf,phone,email,observation) VALUES("","","","","","");

UPDATE clients SET name = "", birthDate = "", cpf = "", phone = "", email = "", address = "", observation = "" WHERE id = 1;

DELETE FROM clients WHERE id = 1;

/* Operações para a tabela addresses */

SELECT * FROM addresses;

SELECT * FROM addresses WHERE id = 1;

INSERT INTO addresses(clientId,cep,street,number,neighborhood,complement,city,state) VALUES(1,123,"","","","","","");

UPDATE addresses SET clientId = 1, cep = 123, street = "", number = "", neighborhood = "", complement = "", city = "", state = "" WHERE id = 1;

DELETE FROM addresses WHERE id = 5;

/* Inserções para teste */
INSERT INTO clients(name,birthDate,cpf,phone,email,observation) VALUES("Bruno Noveli","1999-04-21","47289470842","17997432642","brunozuppwolf@gmail.com","Observando alguma coisa");
INSERT INTO addresses(clientId,cep,street,number,neighborhood,complement,city,state) VALUES(1,15750000,"Rua Cohab II","12345","Bairro Adescau","Casa","Santa Albertina","SP");

INSERT INTO clients(name,birthDate,cpf,phone,email,observation) VALUES("Bruno Zupp","1999-04-21","83491621046","17996589475","brunimzupp@hotmail.com","Observando alguma coisa pela segunda vez");
INSERT INTO addresses(clientId,cep,street,number,neighborhood,complement,city,state) VALUES(2,15700184,"Rua Canada","2487","Jardim Ana Cristina","Casa","Jales","SP");

/* Juntando as tabelas */

SELECT C.*, A.* FROM clients C
INNER JOIN addresses A ON A.clientId = c.id;
