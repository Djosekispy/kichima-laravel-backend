Para documentar sua API Laravel usando Swagger, você pode seguir estes passos:

1. **Instalar o Swagger UI no Laravel**:
   
   Primeiro, você precisa instalar o pacote `darkaonline/l5-swagger`, que facilita a integração do Swagger com o Laravel. Você pode instalá-lo via Composer:

   ```bash
   composer require darkaonline/l5-swagger
   ```

2. **Publicar configurações do Swagger**:

   Após a instalação, você precisa publicar as configurações do Swagger. Isso criará um arquivo de configuração `swagger.php` em `config/`:

   ```bash
   php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
   ```

3. **Configurar o arquivo de configuração do Swagger**:

   No arquivo `config/swagger.php`, você pode ajustar as configurações conforme necessário. Isso inclui definir o caminho para as anotações dos seus controladores, o nome do arquivo JSON gerado e a versão do Swagger que você deseja usar.

4. **Adicionar anotações nos controladores**:

   Você precisará adicionar anotações aos métodos dos seus controladores para documentar sua API. O pacote `darkaonline/l5-swagger` suporta a sintaxe do Swagger PHP, que permite adicionar anotações diretamente nos métodos do controlador. Aqui está um exemplo:

   ```php
   /**
    * @OA\Get(
    *     path="/api/produtos/{id}",
    *     operationId="getProdutoById",
    *     tags={"Produtos"},
    *     summary="Get product information",
    *     description="Retrieves product details by ID",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of the product to retrieve",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             ref="#/components/schemas/Product"
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Product not found"
    *     )
    * )
    */
   public function show($id)
   {
       // Seu código para buscar e retornar os detalhes do produto pelo ID
   }
   ```

5. **Gerar a documentação Swagger**:

   Após adicionar todas as anotações necessárias nos seus controladores, você pode gerar a documentação Swagger executando o comando:

   ```bash
   php artisan l5-swagger:generate
   ```

   Isso gerará um arquivo JSON contendo a documentação da sua API no diretório especificado nas configurações do Swagger.

6. **Acessar a documentação Swagger**:

   Você pode acessar a documentação Swagger através do navegador, indo para a URL definida nas configurações do Swagger. Por padrão, é geralmente algo como `http://localhost:8000/api/documentation`.

Com esses passos, você poderá documentar sua API Laravel usando Swagger e acessar a documentação gerada para visualizar os detalhes dos endpoints da sua API. Certifique-se de manter suas anotações atualizadas à medida que você faz alterações na sua API.
