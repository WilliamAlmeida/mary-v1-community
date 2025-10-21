# Change Log Mary

---


## Componentes

### Colorpicker
O componente `Colorpicker` foi alterado nesta versão. Recomenda-se revisar o commit para detalhes específicos das mudanças, pois não estavam documentadas anteriormente. Caso envolva correções, refatorações ou novos recursos, consulte o diff para mais informações.

### Markdown
O componente `Markdown` também foi modificado. Assim como o Colorpicker, recomenda-se revisar o commit para detalhes das alterações. Documente aqui se houver mudanças relevantes para o usuário final ou para desenvolvedores.

### Dialog
O componente `Dialog` foi aprimorado para:
- Permitir HTML na descrição do diálogo (`x-html` em vez de `x-text`), possibilitando descrições mais ricas.
- Ajustar o z-index do modal para `z-[999]`, garantindo sobreposição correta na interface.
- Corrigir a margem do botão cancelar para `mr-2` (margin-right), melhorando o alinhamento visual.
- Remover logs de debug desnecessários do código.

Essas mudanças tornam o componente mais flexível, visualmente consistente e limpo para produção.

### Code
O componente `Code` foi atualizado para incluir a propriedade `readonly`, permitindo que o editor Ace seja utilizado em modo somente leitura quando necessário. A propriedade é passada diretamente para a opção `readOnly` do Ace Editor via Alpine.js.

**Resumo das mudanças:**
- Adicionada a propriedade pública `readonly` no construtor do componente PHP.
- Passada a opção `readOnly` para o Ace Editor na configuração do Alpine.js.
- Mantida compatibilidade com as demais opções e funcionamento do componente.

### Avatar
**Melhorias e Mudanças Aplicadas:**
- Padronização de slots e propriedades: O novo componente utiliza propriedades mais claras e documentadas para `image`, `placeholder`, `fallbackImage`, `title` e `subtitle`.
- Fallback de imagem: Adicionada a propriedade `fallbackImage`, permitindo exibir uma imagem alternativa caso a principal falhe ao carregar.
- Geração de UUID: O UUID agora é gerado apenas com base no objeto serializado, simplificando a lógica.
- Renderização simplificada: O HTML foi ajustado para ser mais limpo e sem classes desnecessárias.
- Ajuste de classes: Classes CSS para título e subtítulo foram padronizadas para melhor visual e consistência.
- Remoção de propriedades não utilizadas: Propriedades como `id` e `alt` foram removidas para simplificar o componente.
- Documentação: Comentários e anotações de slots e parâmetros foram mantidos e melhorados.

> O componente atualizado está mais enxuto, fácil de manter e com recursos extras (fallback de imagem). Caso precise de mais ajustes ou queira reintroduzir alguma funcionalidade antiga, basta solicitar!

### InputMaskable
O componente `InputMaskable` foi adicionado ao repositório e permite criar campos de input com máscaras customizadas (ex: CPF, CNPJ, datas, telefones, etc), utilizando Alpine.js e integração com Livewire.

**Principais recursos:**
- Máscara customizável via atributo `mask` (ex: `x-mask`)
- Emissão de valor formatado ou limpo (`emitFormatted`)
- Suporte a prefix, suffix, prepend, append
- Label inline com animação
- Ícones à esquerda e à direita, além de botão clearable
- Exibição de erros customizável
- Totalmente reativo com Livewire/Alpine

> Este componente não existia nas versões anteriores do Mary e amplia as possibilidades de inputs avançados no sistema.

### Label
O componente `Label` foi adicionado, permitindo a criação de labels flexíveis com suporte a ícones, badges, tooltips em várias posições e responsividade. Ideal para enriquecer formulários e interfaces com informações contextuais e visuais.

**Principais recursos:**
- Ícone à esquerda e/ou à direita
- Badge customizável
- Tooltip em qualquer posição (top, left, right, bottom)
- Responsividade opcional
- Suporte a slot para conteúdo customizado

> Este componente não existia nas versões anteriores do Mary e oferece mais opções para enriquecer a interface do usuário.

---

## Diferenças entre versões (mary x mary-1.41.8)

### MaryServiceProvider.php
- **Atual**: Importa e registra os componentes `Code` e `Dialog`.
- **Antigo**: Não possui importação nem registro dos componentes `Code` e `Dialog`.
- **Atual**: Registra os componentes `code` e `dialog` com prefixo, além de todos os outros já presentes no antigo.
- **Antigo**: Não registra os componentes `code` e `dialog`.
- **Atual**: Corrige o inglês do comentário de `bellow` para `below`.
- **Antigo**: Comentário com erro de digitação: `bellow`.
- Estrutura dos métodos, lógica de registro de componentes, diretivas Blade e comandos de console são idênticas.
- Ambos usam o prefixo configurável para os componentes.
- **Atual**: Está mais completo, atualizado e com mais componentes registrados.
- **Antigo**: Serve apenas como referência, com menos componentes e pequenos detalhes desatualizados.
