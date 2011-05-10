# ------------------------------------------------------------------------------
# ----------------------------- AUTENTICACAO -----------------------------------
# ------------------------------------------------------------------------------

INSERT INTO NUMENOR.AUTENTICACAO (USUARIO, SENHA, TIPO_USUARIO) VALUES ('numenor', 'luser', 'admins');


# ------------------------------------------------------------------------------
# -------------------------- TIPO DE BANCO DE DADOS ----------------------------
# ------------------------------------------------------------------------------

INSERT INTO CONFIG.TIPO_BANCO_DADOS (NOME, ADAPTADOR) VALUES ('MySql', 'PDO_MYSQL');
INSERT INTO CONFIG.TIPO_BANCO_DADOS (NOME, ADAPTADOR) VALUES ('PostgreSQL', 'PDO_PGSQL');