# Plenamata
Este repositório contem o plugin plenamata-plugin, destinado a customização do jeo-theme e criação de funcionalidades A criação do portal Plenamata.\
**OBSERVAÇÃO:** Neste projeto as customizações e funcionalidades serão feitas dentro do plugin *plenamata-plugin* dessa forma, neste projeto, o jeo-theme e jeo-plugin não estão versionados e não serão modificados para receber essas funcionalidades, estes somente poderão ser modificados nos respectivos repositórios para, por exemplo, receber hooks (action/filters) para que possam ser extendidos por plugins como o *plenamata-plugin*.

## Desenvolvimento:
Você deve solicitar:
- dump de produção
- pasta de plugins
- pasta de temas
## Passo a Passo para instalação local com dump do plugin all in one migrate
Com o ambiente já clonado pelo Git e **antes de importar o backup do AIO Migrate** rode o script *./dev-scripts/composer-build*.
Após rodar esse script, faça a instalação do plugin e a importação do arquivo no mesmo.

## Comandos e informações úteis ##

**Importar o dump rodar:**
```dev-scripts/importdump caminho_do_dump.sql```

**Acessar o container wordpress:**
```dev-scripts/wp```

**Acessar o mysql:**
```dev-scripts/mysql```


**Acessar o mysql como root:**
```dev-scripts/mysql-root```

**Alterar limites de uploads localmente:**

Adicionar as seguintes linhas ao arquivo compose/local/wordpress/php/extra.ini:

```upload_max_filesize = 40000M```

```post_max_size = 40000M```


Temas do projeto:
- newspack
- jeo-theme

Plugins
- newspack
- jeo-plugin
