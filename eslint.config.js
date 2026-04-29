import prettierConfig from 'eslint-config-prettier';

export default [
	{
		ignores: ['src/js/plugins/**/*.js']
	},
	{
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'module',
			globals: {
				browser: true,
				node: true,
				commonjs: true,
				es6: true,
				jquery: true,
				document: 'readonly',
				window: 'readonly',
				console: 'readonly',
				jQuery: 'readonly',
				$: 'readonly'
			}
		},
		rules: {
			'arrow-parens': [2, 'as-needed'],
			'quote-props': [2, 'consistent'],
			'quotes': [2, 'single'],
			'comma-dangle': [2, 'never'],
			'array-bracket-spacing': [2, 'never']
		}
	},
	prettierConfig
];
