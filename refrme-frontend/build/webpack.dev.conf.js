'use strict'
const path = require('path');
const webpack = require('webpack');
const { merge } = require('webpack-merge');
const portfinder = require('portfinder');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const VueLoaderPlugin = require('vue-loader').VueLoaderPlugin;

const baseWebpackConfig = require('./webpack.base.conf');
const config = require('../config');
const utils = require('./utils');

const HOST = process.env.HOST || config.dev.host;
const PORT = process.env.PORT || config.dev.port;

const devWebpackConfig = merge(baseWebpackConfig, {
  mode: 'development',
  devtool: 'eval-cheap-module-source-map',
  module: {
    rules: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap, usePostCSS: true }),
  },
  devServer: {
    client: {
      logging: 'info',
      overlay: config.dev.errorOverlay ? { warnings: false, errors: true } : false,
    },
    historyApiFallback: {
      rewrites: [{ from: /.*/, to: path.posix.join(config.dev.assetsPublicPath, 'index.html') }],
    },
    hot: true,
    static: {
      directory: path.join(__dirname, '../static'),
      publicPath: config.dev.assetsPublicPath,
    },
    compress: true,
    host: HOST,
    port: PORT,
    open: config.dev.autoOpenBrowser,
    proxy: config.dev.proxyTable,
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('development'),
    }),

    // Inject .env from root or config dir
    new Dotenv({
      path: path.resolve(__dirname, '../config/.env'), // correct path
      safe: false,
    }),

    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: 'index.html',
      inject: true,
    }),

    new CopyWebpackPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, '../static'),
          to: config.dev.assetsSubDirectory,
          globOptions: {
            ignore: ['.*'],
          },
        },
      ],
    }),

    new VueLoaderPlugin(),
  ],
});

// 👇 Wrap with portfinder if needed (for dynamic dev port)
module.exports = new Promise((resolve, reject) => {
  portfinder.basePort = PORT;
  portfinder.getPort((err, port) => {
    if (err) {
      reject(err);
    } else {
      process.env.PORT = port;
      devWebpackConfig.devServer.port = port;

      console.log(`🚀 Dev server running: http://${HOST}:${port}`);
      resolve(devWebpackConfig);
    }
  });
});
