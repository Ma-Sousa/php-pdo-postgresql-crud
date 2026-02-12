<?php 
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Database;
use App\CustomerRepository;

final class CustomerRepositoryTest extends TestCase
{
    private \PDO $pdo;
    private CustomerRepository $repo;

    protected function setUp(): void
    {
        $config = require __DIR__ . '/../config.test.php';

        $db = new Database($config);
        $this->pdo = $db->pdo();
        $this->pdo->beginTransaction();
        $this->repo = new CustomerRepository($this->pdo);
    }

    protected function tearDown(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    public function testCreateReturnsIntAndPersistsCustomer(): void
    {
        $id = $this->repo->create('Matheus', 'matheus@test.com');

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
        
        $row = $this->repo->findById($id);
        $this->assertNotNull($row);

        $this->assertSame('Matheus', $row['name']);
        $this->assertSame('matheus@test.com', $row['email']);
    }

    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        $this->assertNull($this->repo->findById(99999999));
    }

    public function testCreateThrowsWhenEmailIsDuplicate(): void
    {
        $this->repo->create('Matheus', 'dum@test.com');

        $this->expectException(\PDOException::class);
        $this->repo->create('Outro', 'dum@test.com');
    }

    public function testUpdatePersistsChanges(): void // void pro teste não retronar nada, só passar ou falhar
    {
        //usando a repo pra inserir um registro no DB de testes, create retorna o id recém criado que será dado o update
        $id = $this->repo->create('Matheus', 'before@test.com');
        //uso a função update , dado o id, o teste atualiza os campos e mantém
        $update = $this->repo->update($id, 'Matheus Atualizado', 'after@test.com');
        $this->assertTrue($update);
        // o row lê o banco de novo, validando que a alteração foi persistida nobanco
        $row = $this->repo->findById($id);
        //garante que o cliente ainda existe depois do update, e avisa se por algum motivo o update apagar ou id não existir
        $this->assertNotNull($row);
        //assertSame para comparar o valor e tipo, confirmando que o banco agora tem os dados atualizados de fato
        $this->assertSame('Matheus Atualizado', $row['name']);
        $this->assertSame('after@test.com', $row['email']);
    }

    public function testDeleteRemovesCustomer(): void    
    {
        $id = $this->repo->create('Matheus', 'del@test.com');

        $delete = $this->repo->delete($id);

        $this->assertTrue($delete);
        $this->assertNull($this->repo->findById($id));
    }

    public function testUpdateReturnsFalseWhenIdDoesNotExist(): void
    {
        $update = $this->repo->update(99999999, 'X', 'x@test.com');
        $this->assertFalse($update);
    }

    public function testCreateAllowsEmptyEmail(): void
    {
        $id = $this->repo->create('No email', '');
        $row = $this->repo->findById($id);

        $this->assertNotNull($row);
        $this->assertSame('No email', $row['name']);
        $this->assertNull($row['email']);
    }


    public function testCountWithQuery(): void
    {
        $this->repo->create('Ana Silva', 'ana@test.com');
        $this->repo->create('Bruna Sousa', 'bruno@test.com');
        $this->repo->create('Carlos', 'carlos@test.com');
        
        $this->assertSame(1, $this->repo->count('sousa'));
    }
    
    public function testGetPageWithQueryReturnsMatches(): void
    {
        $this->repo->create('Ana Silva', 'ana@test.com');
        $this->repo->create('Bruno Sousa', 'bruno@test.com');

        $rows = $this->repo->getPage('sousa', 10, 0);

        $this->assertCount(1, $rows);
        $this->assertSame('Bruno Sousa', $rows[0]['name']);
        $this->assertArrayHasKey('created_at', $rows[0]);
    }
}