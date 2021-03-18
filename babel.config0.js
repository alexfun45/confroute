const presets = [
  [
    "babel-preset-es2015",
    {
      "targets": "> 99%, not dead"
      //useBuiltIns: "usage",
      //corejs: "3.6.4",
    },
  ],
];
const plugins = [
	"@babel/plugin-transform-arrow-functions", 
	"@babel/plugin-transform-runtime"
]

module.exports = { presets };