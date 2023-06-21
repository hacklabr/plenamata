# Referência da API

- Base da URL: `https://api.plenamata.eco/api`
- Método HTTP: `GET`

## Metadados

### `/deter/last_date`

Retorna a última data com dados do DETER disponíveis, e dados correlatos.

#### Resposta

Objeto.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `last_sync` | string | Data da última sincronização (`YYYY-MM-DD`) |
| `deter_last_date` | string | Data da última atualização disponível do DETER (`YYYY-MM-DD`) |
| `deter_first_date` | string | Data do dado mais antigo do DETER disponível (`YYYY-MM-DD`) |
| `week` | int | Semana do ano (1-52+) do último dado disponível |

Exemplo de retorno:

```jsonc
{
  "last_sync": "2023-06-21",
  "deter_last_date": "2023-06-09",
  "deter_first_date": "2016-08-02",
  "week": 23
}
```

### `/deter/lista/municipio`

Lista os municípios.

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `municipio` | string | Nome do município |
| `mun_geo_cod` | string | Código IBGE do município |
| `uf` | string | Sigla do estado |
| `uf_geo_cod` | string | Código IBGE do estado |
| `long` | string | Longitude |
| `lat` | string | Latitude |

Exemplo de retorno:

```jsonc
[
  {
    "municipio": "Abaetetuba",
    "mun_geo_cod": "1500107",
    "uf": "PA",
    "uf_geo_cod": "15",
    "long": "-48.8551263719296",
    "lat": "-1.7391818995"
  },
  /*...*/
]
```

### `/deter/lista/uc`

Lista as unidades de conservação.

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `uc` | string | Nome completo da unidade de conservação (em letras maiúsculas) |
| `code` | int | Código único da unidade de conservação |
| `long` | string | Longitude |
| `lat` | string | Latitude |

Exemplo de retorno:

```jsonc
[
  {
    "uc": "FLORESTA ESTADUAL DE RENDIMENTO SUSTENTADO MUTUM",
    "code": 13310,
    "long": "-62.5158258155522",
    "lat": "-9.44357097356912"
  },
  /*...*/
]
```

### `/deter/lista/ti`

Lista as terras indígenas.

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `ti` | string | Nome da terra indígena |
| `code` | int | Código único da terra indígena |
| `long` | string | Longitude |
| `lat` | string | Latitude |

Exemplo de retorno:

```jsonc
[
  {
    "ti": "Porto Limoeiro",
    "code": 57101,
    "long": "-68.9412972184194",
    "lat": "-3.04931848602524"
  },
  /*...*/
]
```

## Dados do DETER

### `/deter/basica`

Dados acumulados de todo a Amazônia brasileira.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `data1` | string | Sim | Data inicial (`YYYY-MM-DD`) |
| `data2` | string | Sim | Data final (`YYYY-MM-DD`, intervalo fechado) |
| `group_by` | `semana` \| `mes` \| `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `areamunkm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |
| `month` | int | Semana do ano (1-52+) | Apenas para dados agrupados por mês |
| `week` | int | Mês (1-12) | Apenas para dados agrupados por semana |

Exemplo de resposta:

```jsonc
[
  {
    "areamunkm": "1174.741817680953243461055",
    "num_arvores": 66372913
  }
]
```

### `/deter/estados`

Dados acumulados de um estado.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `estado` | string | Sim | Sigla do estado |
| `data1` | string | Sim | Data inicial (`YYYY-MM-DD`) |
| `data2` | string | Sim | Data final (`YYYY-MM-DD`, intervalo fechado) |
| `group_by` | `semana` \| `mes` \| `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `uf` | string | Sigla do estado |
| `areamunkm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |
| `month` | int | Semana do ano (1-52+) | Apenas para dados agrupados por mês |
| `week` | int | Mês (1-12) | Apenas para dados agrupados por semana |

Exemplo de resposta:

```jsonc
[
  {
    "uf": "AM",
    "areamunkm": "276.2782010194030856531",
    "num_arvores": 15609719
  }
]
```

### `/deter/municipios`

Dados acumulados de um município.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `geocode` | string | Sim | Código IBGE do município |
| `data1` | string | Sim | Data inicial (`YYYY-MM-DD`) |
| `data2` | string | Sim | Data final (`YYYY-MM-DD`, intervalo fechado) |
| `group_by` | `semana` \| `mes` \| `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `municipio` | string | Nome do município |
| `geo_cod` | string | Código IBGE do município |
| `areamunkm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |
| `month` | int | Semana do ano (1-52+) | Apenas para dados agrupados por mês |
| `week` | int | Mês (1-12) | Apenas para dados agrupados por semana |

Exemplo de resposta:

```jsonc
[
  {
    "municipio": "Abaetetuba",
    "geo_cod": "1500107",
    "areamunkm": "0.9436179119651796",
    "num_arvores": 53315
  }
]
```

### `/deter/ti`

Dados acumulados de uma terra indígena.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `cod` | string | Sim | Código único da terra indígena |
| `data1` | string | Sim | Data inicial (`YYYY-MM-DD`) |
| `data2` | string | Sim | Data final (`YYYY-MM-DD`, intervalo fechado) |
| `group_by` | `semana` \| `mes` \| `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `terra_indigena` | string | Nome da terra indígena |
| `areatikm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |
| `month` | int | Semana do ano (1-52+) | Apenas para dados agrupados por mês |
| `week` | int | Mês (1-12) | Apenas para dados agrupados por semana |

Exemplo de resposta:

```jsonc
[
  {
    "terra_indigena": "Alto Rio Negro",
    "areatikm": "0.07819876731150027",
    "num_arvores": 4419
  }
]
```

### `/deter/uc`

Dados acumulados de uma unidade de conservação.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `cod` | string | Sim | Código único da unidade de conservação |
| `data1` | string | Sim | Data inicial (`YYYY-MM-DD`) |
| `data2` | string | Sim | Data final (`YYYY-MM-DD`, intervalo fechado) |
| `group_by` | `semana` \| `mes` \| `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `uc` | string | Nome da unidade de conservação (em letras maiúsculas) |
| `areauckm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |
| `month` | int | Semana do ano (1-52+) | Apenas para dados agrupados por mês |
| `week` | int | Mês (1-12) | Apenas para dados agrupados por semana |

Exemplo de resposta:

```jsonc
[
  {
    "uc": "PARQUE ESTADUAL DO XINGU",
    "areauckm": "0.0023671517103910447",
    "num_arvores": 134
  }
]
```

## Dados do PRODES

### `/prodes/estados`

Dados acumulados de um estado.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `estado` | string | Sim | Sigla do estado |
| `ano1` | string | Sim | Ano inicial |
| `ano2` | string | Sim | Ano final |
| `group_by` | `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `uf` | string | Sigla do estado |
| `areakm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |

Exemplo de resposta:

```jsonc
[
  {
    "uf": "AC",
    "areakm": "26164.7071120692",
    "num_arvores": 1478305952
  }
]
```

### `/prodes/municipios`

Dados acumulados de um município.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `geocode` | string | Sim | Código IBGE do município |
| `ano1` | string | Sim | Ano inicial |
| `ano2` | string | Sim | Ano final |
| `group_by` | `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `municipio` | string | Nome do município |
| `geo_cod` | string | Código IBGE do município |
| `areamunkm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |

Exemplo de resposta:

```jsonc
[
  {
    "year": 2007,
    "municipio": "Maués",
    "geo_cod": "1302900",
    "areamunkm": "1253.2834121811165",
    "num_arvores": 70810513
  }
]
```

### `/prodes/taxaano`

Dados acumulados de todo a Amazônia brasileira.

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `year` | int | Ano
| `areakm` | string | Área desmatada (em km²) |

Exemplo de resposta:

```jsonc
[
  {
    "year": 1988,
    "areakm":"21050"
  },
  /*...*/
]
```

### `/prodes/taxaanoestado`

Dados acumulados de cada estado.

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição |
| --- | --- | --- |
| `uf` | string | Nome do estado |
| `year` | int | Ano
| `areakm` | string | Área desmatada (em km²) |

Exemplo de resposta:

```jsonc
[
  {
    "uf": "Pará",
    "year": 1995,
    "areakm": "7845"
  },
  /*...*/
]
```

### `/prodes/ti`

Dados acumulados de uma terra indígena.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `cod` | string | Sim | Código único da terra indígena |
| `ano1` | string | Sim | Ano inicial |
| `ano2` | string | Sim | Ano final |
| `group_by` | `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `terra_indigena` | string | Nome da terra indígena |
| `areatikm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |

Exemplo de resposta:

```jsonc
[
  {
    "terra_indigena": "Alto Rio Negro",
    "areatikm": "0.27611473393995567",
    "num_arvores": 15601
  }
]
```

### `/prodes/uc`

Dados acumulados de uma unidade de conservação.

#### Parâmetros `GET`

| Campo | Tipo | Obrigatório | Descrição |
| --- | --- | --- | --- |
| `cod` | string | Sim | Código único da unidade de conservação |
| `ano1` | string | Sim | Ano inicial |
| `ano2` | string | Sim | Ano final |
| `group_by` | `ano` | Não | Critério de agrupamento |

#### Resposta

Array de objetos.

| Campo | Tipo | Descrição | Presença |
| --- | --- | --- | --- |
| `uc` | string | Nome da unidade de conservação (em letras maiúsculas) |
| `areauckm` | string | Área desmatada (em km²) |
| `num_arvores` | int | Árvores desmatadas |
| `year` | int | Ano | Apenas para dados agrupados |

Exemplo de resposta:

```jsonc
[
  {
    "uc": "ÁREA DE PROTEÇÃO AMBIENTAL DE ALTER DO CHÃO",
    "areauckm": "69.0443600645901",
    "num_arvores":3901007
  }
]
```
v