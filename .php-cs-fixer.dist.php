<?php

declare(strict_types=1);

use PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer;
use PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoCommentedOutCodeFixer;
use PhpCsFixerCustomFixers\Fixer\NoDoctrineMigrationsGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessDoctrineRepositoryCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesCommaSpacesFixer;
use PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'var',
        'config'
    ])
;

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@Symfony' => true,
        '@PSR2' => true,
        '@PHP74Migration:risky' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,

        // Alias
        'array_push' => true,
        'backtick_to_shell_exec' => true,
        'ereg_to_preg' => true,
        'mb_str_functions' => true,
        'no_alias_functions' => ['sets' => ['@all']],
        'no_alias_language_construct_call' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'pow_to_exponentiation' => true,
        'random_api_migration' => ['replacements' => ['getrandmax' => 'mt_getrandmax', 'rand' => 'mt_rand', 'srand' => 'mt_srand']],
        'set_type_to_cast' => true,

        // Array notation
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_whitespace_before_comma_in_array' => ['after_heredoc' => true],
        'normalize_index_brace' => true,
        'trailing_comma_in_multiline' => ['after_heredoc' => true], //'elements' => ['array'],
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,

        // Basic
        'encoding' => true,
        'non_printable_character' => ['use_escape_sequences_in_strings' => true],
        'braces_position' => [
            'allow_single_line_empty_anonymous_classes' => true,

        ],
        'single_space_around_construct' => [
            'constructs_followed_by_a_single_space' => [
                'abstract',
                'as',
                'attribute',
                'break',
                'case',
                'catch',
                'class',
                'clone',
                'const',
                'const_import',
                'continue',
                'do',
                'echo',
                'else',
                'elseif',
                'extends',
                'final',
                'finally',
                'for',
                'foreach',
                'function',
                'function_import',
                'global',
                'goto',
                'if',
                'implements',
                'include',
                'include_once',
                'instanceof',
                'insteadof',
                'interface',
                'match',
                'named_argument',
                'new',
                'open_tag_with_echo',
                'php_open',
                'print',
                'private',
                'protected',
                'public',
                'require',
                'require_once',
                'return',
                'static',
                'throw',
                'trait',
                'try',
                'use',
                'use_lambda',
                'use_trait',
                'var',
                'while',
                'yield',
                'yield_from',
            ],
        ],

        // Casing
        'constant_case' => ['case' => 'lower'],
        'cast_spaces' => ['space' => 'single'],
        'lowercase_cast' => true,
        'modernize_types_casting' => true,
        'no_short_bool_cast' => true,
        'no_unset_cast' => true,
        'short_scalar_cast' => true,

        // Class notation
        'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one', 'trait_import' => 'none']],
        'class_definition' => ['single_line' => true, 'single_item_single_line' => true, 'multi_line_extends_each_single_line' => true],
        'no_blank_lines_after_class_opening' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unneeded_final_method' => ['private_methods' => true],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'method_public_abstract',
                'method_protected_abstract',
                'method_public_abstract_static',
                'method_protected_abstract_static',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_interfaces' => ['order' => 'alpha', 'direction' => 'ascend'],
        'ordered_traits' => true,
        'protected_to_private' => true,
        'self_accessor' => true,
        'self_static_accessor' => true,
        'single_class_element_per_statement' => ['elements' => ['const', 'property']],
        'single_trait_insert_per_statement' => true,
        'visibility_required' => ['elements' => ['property', 'method', 'const']],

        // Comment
        'comment_to_phpdoc' => ['ignored_tags' => []],
        'header_comment' => false,
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_line_comment_style' => ['comment_types' => ['asterisk', 'hash']],

        // Constant notation
        'native_constant_invocation' => ['fix_built_in' => true, 'include' => [], 'exclude' => ['null', 'false', 'true'], 'scope' => 'all', 'strict' => false],


        // Control structure
        'elseif' => true,
        'include' => true,
        'no_alternative_syntax' => true,
        'no_break_comment' => false,
        'no_superfluous_elseif' => false,
        'no_unneeded_control_parentheses' => ['statements' => ['break', 'clone', 'continue', 'echo_print', 'return', 'switch_case', 'yield']],
        'no_unneeded_braces' => true,
        'no_useless_else' => true,
        'simplified_if_return' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'switch_continue_to_break' => true,
        'yoda_style' => ['equal' => true, 'identical' => true, 'less_and_greater' => true, 'always_move_variable' => true],

        // Doctrine annotation
        'doctrine_annotation_array_assignment' => ['operator' => '='],
        'doctrine_annotation_braces' => ['syntax' => 'without_braces'],
        'doctrine_annotation_indentation' => ['indent_mixed_lines' => true],
        'doctrine_annotation_spaces' => [
            'around_parentheses' => true,
            'around_commas' => true,
            'before_argument_assignments' => false,
            'after_argument_assignments' => false,
            'before_array_assignments_equals' => true,
            'after_array_assignments_equals' => true,
            'before_array_assignments_colon' => true,
            'after_array_assignments_colon' => true,
        ],


        // Function notation
        'combine_nested_dirname' => true,
        'fopen_flag_order' => true,
        'fopen_flags' => ['b_mode' => true],
        'function_declaration' => ['closure_function_spacing' => 'none'],
        'type_declaration_spaces' => true,
        'implode_call' => true,
        'lambda_not_used_import' => true,
        'method_argument_space' => ['keep_multiple_spaces_after_comma' => false, 'on_multiline' => 'ensure_fully_multiline', 'after_heredoc' => true],
        'native_function_invocation' => ['include' => ['@internal'], 'scope' => 'all', 'strict' => false],
        'no_spaces_after_function_name' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_sprintf' => true,
        'regular_callable_call' => true,
        'return_type_declaration' => ['space_before' => 'none'],
        'single_line_throw' => false,
        'static_lambda' => true,
        'use_arrow_functions' => true,
        'void_return' => true,

        // Import
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => ['import_constants' => false, 'import_functions' => false, 'import_classes' => false],
        'group_import' => false,
        'no_leading_import_slash' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,

        // Language construct
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'declare_equal_normalize' => ['space' => 'none'],
        'dir_constant' => true,
        'error_suppression' => ['noise_remaining_usages' => true],
        'explicit_indirect_variable' => true,
        'function_to_constant' => ['functions' => ['get_called_class', 'get_class', 'get_class_this', 'php_sapi_name', 'phpversion', 'pi']],
        'no_unset_on_property' => true,
        'list_syntax' => ['syntax' => 'short'],

        // Namespace notation
        'blank_line_after_namespace' => true,
        'clean_namespace' => true,
        'blank_lines_before_namespace' => true,
        'no_leading_namespace_whitespace' => true,

        // Naming
        'no_homoglyph_names' => true,

        // Operator
        'binary_operator_spaces' => ['default' => 'single_space', 'operators' => []],
        'concat_space' => ['spacing' => 'none'],
        'logical_operators' => true,
        'new_with_parentheses' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => ['only_booleans' => false, 'position' => 'beginning'],
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_elvis_operator' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,

        // PHP tag
        'blank_line_after_opening_tag' => true,
        'echo_tag_syntax' => ['format' => 'long', 'long_function' => 'echo', 'shorten_simple_statements_only' => true],
        'full_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        'no_closing_tag' => true,


        // Return notation
        'no_useless_return' => true,
        'return_assignment' => true,
        'simplified_null_return' => false,


        // Semicolon
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],

        // Strict
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,

        // String notation
        'string_implicit_backslashes' => ['single_quoted' => 'unescape', 'double_quoted' => 'escape', 'heredoc' => 'escape'],
        'explicit_string_variable' => true,
        'heredoc_to_nowdoc' => true,
        'no_binary_string' => true,
        'no_trailing_whitespace_in_string' => true,
        'simple_to_complex_string_variable' => true,
        'single_quote' => ['strings_containing_single_quote_chars' => true],
        'string_line_ending' => true,

        // Whitespace
        'array_indentation' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'default',
                'do',
                'exit',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'compact_nullable_type_declaration' => true,
        'heredoc_indentation' => ['indentation' => 'start_plus_one'],
        'indentation_type' => true,
        'line_ending' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
        'spaces_inside_parentheses' => false,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,

        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],

        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_types' => true,
        'phpdoc_indent' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'phpdoc_var_without_name' => false,
        'phpdoc_to_comment' => false,
        'no_empty_phpdoc' => true,
        'statement_indentation' => true,

        'phpdoc_param_order' => true,
        'php_unit_data_provider_name' => false,

////    https://github.com/kubawerlos/php-cs-fixer-custom-fixers#nouselesscommentfixer
        CommentSurroundedBySpacesFixer::name() => true,
        DeclareAfterOpeningTagFixer::name() => false,
        MultilinePromotedPropertiesFixer::name() => true,
        NoCommentedOutCodeFixer::name() => true, // because dead code is trash
        NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
        NoDuplicatedArrayKeyFixer::name() => true,
        NoDuplicatedImportsFixer::name() => true,
        NoImportFromGlobalNamespaceFixer::name() => true,
        NoPhpStormGeneratedCommentFixer::name() => true,
        NoUselessCommentFixer::name() => true,
        NoUselessDoctrineRepositoryCommentFixer::name() => true,
        NoUselessParenthesisFixer::name() => true,
        NoUselessStrlenFixer::name() => true,
        PhpdocNoSuperfluousParamFixer::name() => true,
        PhpdocParamTypeFixer::name() => true,
        PhpdocSelfAccessorFixer::name() => true,
        PhpdocSingleLineVarFixer::name() => true,
        PhpdocTypesCommaSpacesFixer::name() => true,
        StringableInterfaceFixer::name() => true,
    ])
    ->setFinder($finder);
