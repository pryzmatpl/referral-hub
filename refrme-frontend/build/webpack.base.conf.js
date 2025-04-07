const path = require("path");
const utils = require("./utils");
const webpack = require("webpack");
const vueLoaderConfig = require('./vue-loader.conf')
const { VueLoaderPlugin } = require('vue-loader');
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { BundleAnalyzerPlugin } = require("webpack-bundle-analyzer");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const Dotenv = require("dotenv-webpack");
const buildAssetsRoot = path.resolve(__dirname, "../dist");
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

module.exports = {
  context: path.resolve(__dirname, "../"),
  entry: {
    app: path.resolve(__dirname, "../src/main.js")
  },
  output: {
    path: buildAssetsRoot,
    filename: utils.assetsPath("js/[name].[contenthash].js"),
    chunkFilename: utils.assetsPath("js/[name].[contenthash].js"),
    publicPath: "/",  // Use '/' or another valid URL path
    clean: true,
  },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      "vue$": "vue/dist/vue.esm-bundler.js",
      "@": path.resolve(__dirname, "../src"),
    },
  },
  optimization: {
    splitChunks: {
      chunks: "all",
    },
    minimize: true,
    minimizer: [
      new TerserPlugin(),
      new CssMinimizerPlugin(),
    ],
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
        options: {
          vueLoaderConfig
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        include: [path.resolve(__dirname, '../src'), path.resolve(__dirname, '../test')],
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("img/[name].[hash:7][ext]")
        }
      },
      {
        test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("media/[name].[hash:7][ext]")
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("fonts/[name].[hash:7][ext]")
        }
      },
      {
        test: /\.s[ac]ss$/i,
        exclude: /\.vue$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "sass-loader",
            options: {
              sassOptions: {
                indentedSyntax: false,
                includePaths: [path.resolve(__dirname, "../src/assets")]
              }
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
    ],
  },
  plugins: [
    new webpack.DefinePlugin({ ...definitions }),
    new webpack.DefinePlugin({
      'process.env': JSON.stringify(dotenv.parsed), // or a filtered set
    }),
    new VueLoaderPlugin(),
    new webpack.ProvidePlugin({
      $: "jquery",
      jquery: "jquery",
      "window.jQuery": "jquery",
      jQuery: "jquery",
    }),
    new HtmlWebpackPlugin({
      template: path.resolve(__dirname, '../static/index.html'),
      inject: true,
    }),
    new MiniCssExtractPlugin({
      filename: utils.assetsPath("css/[name].[contenthash].css"),
      chunkFilename: utils.assetsPath("css/[id].[contenthash].css"),
    }),
    new BundleAnalyzerPlugin(),
  ],
};