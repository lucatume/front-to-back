var path = require( 'path' );

module.exports = {
	entry: {
		'ftb-customizer': './assets/js/customizer.js',
		'ftb-customizer-iframe': './assets/js/customizer-iframe.js'
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
			path.resolve( './assets/js/globals' ),
			path.resolve( './assets/scss' ),
		],
		extensions: ['', '.js', '.scss','.css'],
	}
};