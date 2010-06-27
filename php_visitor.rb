# Warning: nowhere near complete!
# Large portions are still the same as ECMAVisitor!

require 'json'
$SYMBOL_STACK = [
    {
        'function' => {
            'print' => 'echo',
            'require' => 'CommonJS::_require',
            'RegExp' => 'PHECMA_RegExp'
        },
        'var' => {
        }
    }
]

module RKelly
    module Nodes
        class Node
            def to_php
                PHPVisitor.new.accept(self)
            end
        end
    end
    module Visitors
        class PHPVisitor < Visitor
            def initialize
                @indent = 0
            end

            def visit_SourceElementsNode(o)
                o.value.map { |x| "#{indent}#{x.accept(self)}" }.join("\n")
            end

            def visit_VarStatementNode(o)
               "#{o.value.map { |x| '$' + x.accept(self) }.join(', ')};"
            end

            def visit_ConstStatementNode(o)
                # FIXME PHP constants are global; JS too?
                o.value.map { |x| x.accept(self) }.map { |x|
                    m = x.match(/^\s*(\S+)\s+=\s+(.+)$/)
                    "define('#{m[1]}', #{m[2]})"
                }.join(";\n") + ';'
            end

            def visit_VarDeclNode(o)
                $SYMBOL_STACK[-1]['var'][o.name] = o.name
                "#{o.name}#{o.value ? o.value.accept(self) : nil}"
            end

            def visit_AssignExprNode(o)
                " = #{o.value.accept(self)}"
            end

            def visit_NumberNode(o)
                o.value.to_s
            end

            def visit_ForNode(o)
                init    = o.init ? o.init.accept(self) : ';'
                test    = o.test ? o.test.accept(self) : ''
                counter = o.counter ? o.counter.accept(self) : ''
                "for(#{init} #{test}; #{counter}) #{o.value.accept(self)}"
            end

            def visit_LessNode(o)
                "$#{o.left.accept(self)} < #{o.value.accept(self)}"
            end

            def visit_ResolveNode(o)
                if $SYMBOL_STACK[-1]['function'].has_key?(o.value) # FIXME
                    return $SYMBOL_STACK[-1]['function'][o.value]
                else
                    return '$' + o.value # FIXME add to symbol table?
                end
            end

            def visit_PostfixNode(o)
                "#{o.operand.accept(self)}#{o.value}"
            end

            def visit_PrefixNode(o)
                "#{o.value}$#{o.operand.accept(self)}"
            end

            def visit_BlockNode(o)
                @indent += 1
                "{\n#{o.value.accept(self)}\n#{@indent -=1; indent}}"
            end

            def visit_ExpressionStatementNode(o)
                "#{o.value.accept(self)};"
            end

            def visit_OpEqualNode(o)
                "#{o.left.accept(self)} = #{o.value.accept(self)}"
            end

            def visit_FunctionCallNode(o)
                "#{o.value.accept(self)}(#{o.arguments.accept(self)})"
            end

            def visit_ArgumentsNode(o)
                o.value.map { |x| x.accept(self) }.join(', ')
            end

            def visit_StringNode(o)
                o.value
            end

            def visit_NullNode(o)
                'NULL'
            end

            def visit_FunctionDeclNode(o)
                $SYMBOL_STACK[-1]['function'][o.value] = o.value # FIXME
                $SYMBOL_STACK.push($SYMBOL_STACK[-1].dup)

                clvar = $SYMBOL_STACK[-1]['var'].keys.map { |k| '&$' + k }.
                    join(', ')

                args = o.arguments.map { |x| x.accept(self) }
                args.map { |var| var.sub(/^\$/, '') }.each { |var|
                    $SYMBOL_STACK[-1]['var'][var] = var
                }

                fdn = "#{indent}function #{o.value}(" + args.join(', ') + ')'

                if clvar.length > 0
                    fdn += " use (#{clvar})"
                end

                fdn += " #{o.function_body.accept(self)}"

                return fdn
            end

            def visit_ParameterNode(o)
                '$' + o.value
            end

            def visit_FunctionBodyNode(o)
                @indent += 1
                fb = "{\n#{o.value.accept(self)}\n#{@indent -=1; indent}}"
# fb += "/* SYMBOL STACK:\n" + $SYMBOL_STACK.to_json + "\n*/\n"
                $SYMBOL_STACK.pop
                return fb
            end

            def visit_BreakNode(o)
                "break" + (o.value ? " #{o.value}" : '') + ';'
            end

            def visit_ContinueNode(o)
                "continue" + (o.value ? " #{o.value}" : '') + ';'
            end

            def visit_TrueNode(o)
                "true"
            end

            def visit_FalseNode(o)
                "false"
            end

            def visit_EmptyStatementNode(o)
                ';'
            end

            def visit_RegexpNode(o)
                o.value
            end

            def visit_DotAccessorNode(o)
                "#{o.value.accept(self)}->#{o.accessor}"
            end

            def visit_ThisNode(o)
                "$this"
            end

            def visit_BitwiseNotNode(o)
                "~#{o.value.accept(self)}"
            end

            def visit_DeleteNode(o)
                "unset(#{o.value.accept(self)})" # FIXME value is "$x->v" (wrong)
            end

            def visit_ArrayNode(o)
                "array(#{o.value.map { |x| x ? x.accept(self) : '' }.join(', ')})"
            end

            def visit_ElementNode(o)
                o.value.accept(self)
            end

            def visit_LogicalNotNode(o)
                "!#{o.value.accept(self)}"
            end

            def visit_UnaryMinusNode(o)
                "-#{o.value.accept(self)}"
            end

            def visit_UnaryPlusNode(o)
                "+#{o.value.accept(self)}"
            end

            def visit_ReturnNode(o)
                "return(" + (o.value ? "#{o.value.accept(self)}" : '') + ');'
            end

            def visit_ThrowNode(o)
                "throw #{o.value.accept(self)};"
            end

            def visit_TypeOfNode(o)
                "typeof #{o.value.accept(self)}"
            end

            def visit_VoidNode(o)
                "void(#{o.value.accept(self)})"
            end

      [
        [:Add, '+'],
        [:BitAnd, '&'],
        [:BitOr, '|'],
        [:BitXOr, '^'],
        [:Divide, '/'],
        [:Equal, '=='],
        [:Greater, '>'],
        [:Greater, '>'],
        [:GreaterOrEqual, '>='],
        [:GreaterOrEqual, '>='],
        [:In, 'in'],
        [:InstanceOf, 'instanceof'],
        [:LeftShift, '<<'],
        [:LessOrEqual, '<='],
        [:LogicalAnd, '&&'],
        [:LogicalOr, '||'],
        [:Modulus, '%'],
        [:Multiply, '*'],
        [:NotEqual, '!='],
        [:NotStrictEqual, '!=='],
        [:OpAndEqual, '&='],
        [:OpDivideEqual, '/='],
        [:OpLShiftEqual, '<<='],
        [:OpMinusEqual, '-='],
        [:OpModEqual, '%='],
        [:OpMultiplyEqual, '*='],
        [:OpOrEqual, '|='],
        [:OpPlusEqual, '+='],
        [:OpRShiftEqual, '>>='],
        [:OpURShiftEqual, '>>>='],
        [:OpXOrEqual, '^='],
        [:RightShift, '>>'],
        [:StrictEqual, '==='],
        [:Subtract, '-'],
        [:UnsignedRightShift, '>>>'],
      ].each do |name,op|
        define_method(:"visit_#{name}Node") do |o|
          "#{o.left.accept(self)} #{op} #{o.value.accept(self)}"
        end
            end

            def visit_WhileNode(o)
                "while(#{o.left.accept(self)}) #{o.value.accept(self)}"
            end

            def visit_SwitchNode(o)
                "switch(#{o.left.accept(self)}) #{o.value.accept(self)}"
            end

            def visit_CaseBlockNode(o)
                @indent += 1
                    "{\n" + (o.value ? o.value.map { |x| x.accept(self) }.join('') : '') +
                    "#{@indent -=1; indent}}"
            end

            def visit_CaseClauseNode(o)
                case_code = "#{indent}case #{o.left ? o.left.accept(self) : nil}:\n"
                @indent += 1
                case_code += "#{o.value.accept(self)}\n"
                @indent -= 1
                case_code
            end

            def visit_DoWhileNode(o)
                "do #{o.left.accept(self)} while(#{o.value.accept(self)});"
            end

            def visit_WithNode(o)
                # FIXME make this illegal?
                "with(#{o.left.accept(self)}) #{o.value.accept(self)}"
            end

            def visit_LabelNode(o)
                # PHP 5.3 only?
                "#{o.name}: #{o.value.accept(self)}"
            end

            def visit_ObjectLiteralNode(o)
                @indent += 1
                lit = "array(" + (o.value.length > 0 ? "\n" : ' ') +
                    o.value.map { |x| "#{indent}#{x.accept(self)}" }.join(",\n") +
                    (o.value.length > 0 ? "\n" : '') + ')'
                @indent -= 1
                lit
            end

            def visit_PropertyNode(o)
                "'#{o.name}' => #{o.value.accept(self)}"
            end

            def visit_GetterPropertyNode(o)
                "get #{o.name}#{o.value.accept(self)}"
            end

            def visit_SetterPropertyNode(o)
                "set #{o.name}#{o.value.accept(self)}"
            end

            def visit_FunctionExprNode(o)
                $SYMBOL_STACK.push($SYMBOL_STACK[-1].dup)
                clvar = $SYMBOL_STACK[-1]['var'].keys.map { |k| '&$' + k }.join(', ')

                fen = "#{o.value}(#{o.arguments.map { |x| x.accept(self) }.join(', ')})"
                if clvar.length > 0
                    fen += " use (#{clvar})"
                end
                fen += " #{o.function_body.accept(self)}"
                return fen
            end

            def visit_CommaNode(o)
                "#{o.left.accept(self)}, #{o.value.accept(self)}"
            end

            def visit_IfNode(o)
                "if(#{o.conditions.accept(self)}) #{o.value.accept(self)}" +
                    (o.else ? " else #{o.else.accept(self)}" : '')
            end

            def visit_ConditionalNode(o)
                "#{o.conditions.accept(self)} ? #{o.value.accept(self)} : " +
                    "#{o.else.accept(self)}"
            end

            def visit_ForInNode(o)
                "foreach (#{o.right.accept(self)} as " +
                    "$#{o.left.accept(self)}) " +
                    "#{o.value.accept(self)}"
            end

            def visit_TryNode(o)
                # FIXME PHP doesn't have "finally" does it?
                "try #{o.value.accept(self)}" +
                    (o.catch_block ? " catch($#{o.catch_var}) #{o.catch_block.accept(self)}" : '') +
                    (o.finally_block ? " finally #{o.finally_block.accept(self)}" : '')
            end

            def visit_BracketAccessorNode(o)
                "#{o.value.accept(self)}[#{o.accessor.accept(self)}]"
            end

            def visit_NewExprNode(o)
                "new #{o.value.accept(self)}(#{o.arguments.accept(self)})"
            end

      private
            def indent; ' ' * @indent * 2; end
    end
  end
end
