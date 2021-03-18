const path = require('path')
const HtmlWebpackPlugin = require('html-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const webpack = require('webpack')
const CopyPlugin = require('copy-webpack-plugin')

module.exports = {
	//entry:'./src/index.js',
	entry: [
    '@babel/polyfill',
    './src/index.js'
  ],
	output: {
		filename: '[name].bundle.js',
		publicPath: '',
		path: path.resolve(__dirname, 'dist')
	},
	resolve: {
        alias: {
        		 '@': path.resolve(__dirname, 'src'),
            'vue$': 'vue/dist/vue.esm.js'
        },
        extensions: ['*', '.js', '.vue', '.json', '.ts']
    },
	module: {
    rules: [
      { test: /\.(woff|woff2|eot|ttf|otf|svg)$/,
		  loader: 'file-loader',
		  options: {
		  	 name: '[name].[ext]',
		    outputPath: 'dist/fonts/',
			},
		},
		//{
        //test: /\.(woff|woff2|eot|png|jpe?g|gif|svg)(\?.*)?$/,
        //loader: 'url-loader'
		//},
      { 
         test: /\.js$/, 
       	 exclude: /node_modules/, 
      	 //loader: 'babel-loader',
		 //options: {
			 //"presets": [ ["@babel/preset-env"]],
			 //"presets": [
				//["@babel/preset-env"]
			//],
			//plugins: ['dynamic-import-node', 'transform-class-properties', "@babel/transform-runtime"]
			//},
		 use: {
          	loader: 'babel-loader'
		 },
		 /*use: {
          	loader: 'babel-loader',
			options:{
				presets: ["@babel/preset-env"],
				plugins: [
                        //require("@babel/plugin-transform-async-to-generator"),
                        ["@babel/plugin-transform-arrow-functions"],
                        ["@babel/plugin-transform-modules-commonjs"]
                    ]
				//plugins: [["dynamic-import-node"], ["@babel/plugin-transform-arrow-functions",  {"spec": false }]]
			}
        	 },*/
        	 /*options: {
            presets: [
            	['@babel/preset-env', 
            	{
				      "targets": {
				        "browsers": ["last 1 versions", "safari >= 5"]
				      }
				   }
            	]
            	],
            
       	  },*/
      	 //options: {
          	//presets: [ ['@babel/preset-env', { targets: {"browsers":[  "> 1%"]} }] ],
          	//plugins: ['dynamic-import-node', "transform-es2015-arrow-functions"]
        //} 
     },
	 {
		test: /\.ts$/,
		exclude: /node_modules/,
		use: {
		  loader: "babel-loader",
		  options: {
			presets: ['@babel/preset-env', '@babel/preset-typescript'],
			plugins: [
					'@babel/plugin-proposal-class-properties'	            
			]
		  }
		}
	},
      { test: /\.vue$/, loader: 'vue-loader' },
      //{ test: /\.css$/, use: ['style-loader', 'css-loader']},
      {
        test: /\.s?css$/,
        use: [
            "style-loader", 
            "css-loader", 
            "sass-loader" 
        ]
    }
    ]
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: './src/index.html',
      filename: '../index.html'
    }),
    new VueLoaderPlugin(),
    new CopyPlugin([
			        {
				        from: path.resolve(__dirname, 'src/assets/'),
				        to: path.resolve(__dirname, 'dist/assets/')		
			        }]
		        )
  ]
}