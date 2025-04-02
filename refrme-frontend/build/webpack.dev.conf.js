'use strict'
const path = require('path');
const webpack = require('webpack');
const { merge } = require('webpack-merge');
const portfinder = require('portfinder');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const VueLoaderPlugin = require('vue-loader').VueLoaderPlugin;
const Dotenv = require("dotenv-webpack");
const baseWebpackConfig = require('./webpack.base.conf');
const utils = require('./utils');
const dotenv = require('dotenv').config();

console.log(process.env)

const { definitions } = new Dotenv({
      path: path.resolve(__dirname, '.env'), // load this now instead of the ones in '.env'
      safe: true, // load '.env.example' to verify the '.env' variables are all set. Can also be a string to a different file.
      allowEmptyValues: false, // allow empty variables (e.g. `FOO=`) (treat it as empty string, rather than missing)
      systemvars: false, // load all the predefined 'process.env' variables which will trump anything local per dotenv specs.
      silent: false, // hide any errors
      defaults: false, // load '.env.defaults' as the default values if empty.
      prefix: 'VUE_APP_', // Only include environment variables that start with VUE_APP_
});

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
    host: process.env["VUE_APP_HOST"],
    port: process.env["VUE_APP_PORT"],
    open: false,
    proxy: [],
  },
  plugins: [
    new webpack.DefinePlugin({ ...definitions }),
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
  portfinder.basePort = process.env["VUE_APP_PORT"];
  portfinder.getPort((err, port) => {
    if (err) {
      reject(err);
    } else {
      process.env.PORT = port;
      devWebpackConfig.devServer.port = port;

      console.log(`🚀 Dev server running: http://${process.env["VUE_APP_HOST"]}:${process.env["VUE_APP_PORT"]}`);
      resolve(devWebpackConfig);
    }
  });
});
