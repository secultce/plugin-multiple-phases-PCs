# Plugin Multiple Phases PC
Plugin que habilita várias prestações de conta dentro da plataforma do Mapas Culturais

### CONTEXTO
Atualmente a plataforma [Mapas Culturais](https://github.com/mapasculturais/mapasculturais) não possibilita adicionar mais de uma oportunidade do tipo prestação de contas, esse plugin tem a possibilidade de adicionar outras oportunidades de prestação de contas.

#Habilitação de plugins na máquina local

É necessário que tenha o arquivo **plugins.php** no caminho `compose/common/config.d` para que seja habilitado o plugin para ambiente de desenvolvimento. Também é necessário adicionar o caminho do plugin dentro do arquivo **`docker-compose.yml`** na raiz do projeto.
