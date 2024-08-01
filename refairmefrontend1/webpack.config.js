"use strict"
const path = require("path")
const utils = require("./utils")
const config = require("./config")
const Dotenv = require("dotenv-webpack")
const { VueLoaderPlugin } = require('vue-loader')
const webpack = require("webpack")
const HtmlWebpackPlugin = require("html-webpack-plugin")
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin
const {CleanWebpackPlugin} = require("clean-webpack-plugin")

function resolve(dir) {
  return path.join(__dirname, ".", dir)
}

module.exports = {
  mode: "production",
  context: path.resolve(__dirname, "./"),
  plugins: [
    new VueLoaderPlugin(),
    new webpack.ProvidePlugin({
      $: "jquery",
      jquery: "jquery",
      "window.jQuery": "jquery",
      jQuery: "jquery",
    }),
    new Dotenv({
      path: "./config/.env",
      safe: false,
    }),
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false,
      "process.env": require("./config/dev.env"),
    }),
    new CleanWebpackPlugin(),
    new HtmlWebpackPlugin({
      filename: "index.html",
      template: "index.html",
      inject: true,
    }),
    new BundleAnalyzerPlugin(),    
  ],
  optimization: {
    splitChunks: {
      chunks: "all",
    },
  },
  entry: {
    app: "./src/main.js",
  },
  devServer: {
    hot: true,
  },
  output: {
    path: config.build.assetsRoot,
    filename: "[name].bundle.js",
    chunkFilename: "[name].bundle.js",
    publicPath: process.env.NODE_ENV === "production" ?
      config.build.assetsPublicPath : config.dev.assetsPublicPath,
  },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      "vue": "vue/dist/vue.esm-bundler.js",
      "@": resolve("src"),
    },
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        include: [resolve("src"), resolve("test"), resolve("node_modules/webpack-dev-server/client")],
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
        use: ['vue-style-loader', 'style-loader', 'css-loader',  {
          loader: 'sass-loader',
          options: {
            sassOptions: {
              indentedSyntax: false
            }
          }
        }]
      },
      {
        test: /\.css$/,
        use: ["style-loader", "css-loader"],
      },
    ],
  },
}