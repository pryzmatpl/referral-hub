const fs = require('fs');
const path = require('path')
const utils = require('./utils')
const webpack = require('webpack')
const { merge } = require('webpack-merge')
const Dotenv = require("dotenv-webpack");
const baseWebpackConfig = require('./webpack.base.conf')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const MiniCssExtractPlugin = require("mini-css-extract-plugin")
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const { VueLoaderPlugin } = require('vue-loader')
const TerserPlugin = require('terser-webpack-plugin')

fs.writeFileSync(
    path.resolve(__dirname, '../alias-log.json'),
    JSON.stringify(baseWebpackConfig.resolve?.alias, null, 2)
);

// First, remove the HtmlWebpackPlugin from the base config to avoid conflicts
const filteredPlugins = baseWebpackConfig.plugins.filter(
  plugin => !(plugin instanceof HtmlWebpackPlugin)
);
baseWebpackConfig.plugins = filteredPlugins;

// Also remove the webpack.DefinePlugin to avoid conflicts
baseWebpackConfig.plugins = baseWebpackConfig.plugins.filter(
  plugin => !(plugin instanceof webpack.DefinePlugin)
);

const { definitions } = new Dotenv({
  path: path.resolve(__dirname, '../.env'),
  safe: true,
  allowEmptyValues: false,
  systemvars: true,
  silent: false,
  defaults: false,
  prefix: 'VUE_APP_'
});

const webpackConfig = merge(baseWebpackConfig, {
  mode: 'production',
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader',
        ],
      },
    ]
  },
  devtool: 'source-map',
  output: {
    path: path.resolve(__dirname, "../dist"),
    filename: utils.assetsPath('js/[name].[contenthash].js'),
    chunkFilename: utils.assetsPath('js/[id].[contenthash].js')
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        commons: {
          test: /[\\/]node_modules[\\/]/,
          name: "vendors",
          chunks: "all"
        }
      }
    },
    minimizer: [
      new CssMinimizerPlugin({
        minimizerOptions: {
          sourceMap: true
        }
      }),
      new TerserPlugin({
        terserOptions: {
          compress: {
            drop_console: false,
            drop_debugger: false,
            pure_funcs: []
          },
          mangle: {
            keep_fnames: true,
            keep_classnames: true,
            safari10: true
          },
          output: {
            comments: false,
            beautify: false
          }
        },
        extractComments: false
      })
    ],
  },
  plugins: [
    // Single DefinePlugin with all definitions
    new webpack.DefinePlugin({
      ...definitions,
      'process.env': JSON.stringify(process.env),
      'process.env.NODE_ENV': JSON.stringify('production'),
      '__VUE_OPTIONS_API__': JSON.stringify(true),
      '__VUE_PROD_DEVTOOLS__': JSON.stringify(true),
      '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(true)
    }),
    new MiniCssExtractPlugin({
      filename: utils.assetsPath('css/[name].[contenthash].css'),
    }),
    // Single HtmlWebpackPlugin for production
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: './static/index.html',
      inject: true,
      minify: {
        removeComments: true,
        collapseWhitespace: true,
        removeAttributeQuotes: true
      },
      chunksSortMode: 'auto'
    }),
    new webpack.optimize.ModuleConcatenationPlugin(),
    // Copy static files except index.html
    new CopyWebpackPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, '../static'),
          to: path.resolve(__dirname, '../dist'),
          globOptions: {
            ignore: ['.*', 'index.html']
          }
        }
      ]
    })
  ]
})

console.log('💡 Alias @ resolves to:', webpackConfig.resolve?.alias?.['@']);

module.exports = webpackConfig