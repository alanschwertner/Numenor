

CREATE  TABLE IF NOT EXISTS `estado` (

  `id_estado` INT(11) NOT NULL ,

  `uf` CHAR(2) NULL DEFAULT NULL ,

  `nome` VARCHAR(50) NULL DEFAULT NULL ,

  PRIMARY KEY (`id_estado`) )

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8

COLLATE = utf8_unicode_ci;


CREATE  TABLE IF NOT EXISTS `empresa` (

  `id_empresa` INT(11) NOT NULL ,

  `id_estado` INT(11) NOT NULL ,

  `razao_social` VARCHAR(255) NULL DEFAULT NULL ,

  `nome_fantasia` VARCHAR(255) NULL DEFAULT NULL ,

  `cnpj` VARCHAR(20) NULL DEFAULT NULL ,

  `rua` VARCHAR(200) NULL DEFAULT NULL ,

  `numero` INT(11) NULL DEFAULT NULL ,

  `complemento` VARCHAR(50) NULL DEFAULT NULL ,

  `cidade` VARCHAR(100) NULL DEFAULT NULL ,

  `bairro` VARCHAR(100) NULL DEFAULT NULL ,

  `cep` VARCHAR(10) NULL DEFAULT NULL ,

  PRIMARY KEY (`id_empresa`) ,

  INDEX `fk_empresa_estado` (`id_estado` ASC) ,

  CONSTRAINT `fk_empresa_estado`

    FOREIGN KEY (`id_estado` )

    REFERENCES `estado` (`id_estado` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION)

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8

COLLATE = utf8_unicode_ci;





CREATE  TABLE IF NOT EXISTS `material` (

  `id_material` INT(11) NOT NULL ,

  `nome` VARCHAR(150) NULL DEFAULT NULL ,

  `descricao` TEXT NULL DEFAULT NULL ,

  PRIMARY KEY (`id_material`) )

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8

COLLATE = utf8_unicode_ci;



CREATE  TABLE IF NOT EXISTS `pedido` (

  `id_pedido` INT(11) NOT NULL ,

  `id_empresa` INT(11) NOT NULL ,

  `data_entrega` DATETIME NULL DEFAULT NULL ,

  PRIMARY KEY (`id_pedido`) ,

  INDEX `fk_pedido_empresa1` (`id_empresa` ASC) ,

  CONSTRAINT `fk_pedido_empresa1`

    FOREIGN KEY (`id_empresa` )

    REFERENCES `empresa` (`id_empresa` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION)

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8

COLLATE = utf8_unicode_ci;



CREATE  TABLE IF NOT EXISTS `pedido_material` (

  `id_pedido` INT(11) NOT NULL ,

  `id_material` INT(11) NOT NULL ,

  `quantidade` INT(11) NULL DEFAULT NULL ,

  PRIMARY KEY (`id_pedido`, `id_material`) ,

  INDEX `fk_pedido_has_material_material1` (`id_material` ASC) ,

  INDEX `fk_pedido_has_material_pedido1` (`id_pedido` ASC) ,

  CONSTRAINT `fk_pedido_has_material_pedido1`

    FOREIGN KEY (`id_pedido` )

    REFERENCES `pedido` (`id_pedido` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION,

  CONSTRAINT `fk_pedido_has_material_material1`

    FOREIGN KEY (`id_material` )

    REFERENCES `material` (`id_material` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION)

ENGINE = InnoDB

DEFAULT CHARACTER SET = utf8

COLLATE = utf8_unicode_ci;




