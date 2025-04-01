'use strict'
const path = require('path');
const webpack = require('webpack');
const { merge } = require('webpack-merge');
const portfinder = require('portfinder');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const VueLoaderPlugin = require('vue-loader').VueLoaderPlugin;

const baseWebpackConfig = require('./webpack.base.conf');
const utils = require('./utils');

const HOST = process.env.HOST;
const PORT = process.env.PORT;

const devWebpackConfig = merge(baseWebpackConfig, {
  mode: 'development',
  devtool: 'eval-cheap-module-source-map',
  module: {
    rules: utils.styleLoaders({ sourceMap: true, usePostCSS: true }),
  },
  devServer: {
    client: {
      logging: 'info',
      overlay: { warnings: false, errors: true },
    },
    historyApiFallback: {
      rewrites: [{ from: /.*/, to: path.posix.join(path.resolve(__dirname, "../static"), "index.html") }],
    },
    hot: true,
    static: {
      directory: path.join(__dirname, "../static"),
      publicPath:path.resolve(__dirname, "../static"),
    },
    compress: true,
    host: HOST,
    port: PORT,
    open: false,
    proxy: [],
  },
  plugins: [
    new CopyWebpackPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, "../src/assets"),
          to: path.resolve(__dirname, "../dist"),
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
