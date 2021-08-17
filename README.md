# Plenamata
Este repositório contem o plugin plenamata-plugin, destinado a customização do jeo-theme e criação de funcionalidades A criação do portal Plenamata.

## Desenvolvimento:
Você deve solicitar:
- dump de produção
- pasta de plugins
- pasta de temas

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
