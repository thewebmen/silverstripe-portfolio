const path = require('path');
const Webpack = require('webpack');
const ESLintPlugin = require('eslint-webpack-plugin');
const ESLintConfig = require('./.eslintrc');

const devMode = process.env.NODE_ENV === 'dev' ? 'development' : 'production';

const plugins = [
  new ESLintPlugin({
    overrideConfig: ESLintConfig
  }),
];

module.exports = {
  devtool: process.env.NODE_ENV === 'dev' ? 'source-map' : false,
  mode: devMode,
  watchOptions: {
    ignored: /node_modules/,
  },
  entry: {
    main: './client/src/main.js',
  },
  output: {
    path: path.resolve(__dirname, './client/dist')
  },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
            plugins: [
              '@babel/plugin-transform-runtime',
            ],
          },
        },
      },
    ],
  },
  plugins
};
