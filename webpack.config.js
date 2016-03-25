var path = require( 'path' );

module.exports = {
	entry: {
		'ftb': './assets/js/front-to-back.js',
		'ftb-admin': './assets/js/front-to-back-admin.js',
		'ftb-customizer': './assets/js/front-to-back-customizer.js'
	},
	output: {
		filename: './assets/js/dist/[name].js',
		libraryTarget: 'var',
		library: 'ftb',
	},
	module: {
		loaders: [
			{test: /\.js$/, loader: 'babel-loader', exclude: /node_modules/},
			{test: /\.css$/, loader: 'style!css'},
			{test: /\.scss$/, loaders: ['style', 'css', 'sass']}
		]
	},
	resolve: {
		root: [
			path.resolve( './assets/js/modules' ),
		],
		extensions: ['', '.js'],
	}
};