<?php

namespace Tests\Unit;

use App\Support\Formatar;
use PHPUnit\Framework\TestCase;

class FormatarTest extends TestCase
{
    public function test_moeda_no_padrao_brasileiro(): void
    {
        $this->assertSame('R$ 1.234.567,89', Formatar::moeda('1234567.89'));
        $this->assertSame('R$ 0,00', Formatar::moeda(null));
    }

    public function test_cpf_e_mascarado_e_cnpj_e_formatado(): void
    {
        $this->assertSame('***.456.789-**', Formatar::documentoPublico('123.456.789-01'));
        $this->assertSame('12.345.678/0001-90', Formatar::documentoPublico('12345678000190'));
        $this->assertNull(Formatar::documentoPublico(''));
        $this->assertNull(Formatar::documentoPublico('123'));
    }
}
