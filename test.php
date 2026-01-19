<!DOCTYPE html>
<html>
	<head>
		<script src="/js/jquery.js"></script>
		<script>
			var colours = [
				"White",
				"Grey",
				"Light yellow",
				"Brick yellow",
				"Light green (Mint)",
				"Light reddish violet",
				"Pastel Blue",
				"Light orange brown",
				"Nougat",
				"Bright red",
				"Med. reddish violet",
				"Bright blue",
				"Bright yellow",
				"Earth orange",
				"Black",
				"Dark grey",
				"Dark green",
				"Medium green",
				"Lig. Yellowich orange",
				"Bright green",
				"Dark orange",
				"Light bluish violet",
				"Tr. Red",
				"Tr. Lg blue",
				"Tr. Blue",
				"Tr. Yellow",
				"Light blue",
				"Tr. Flu. Reddish orange",
				"Tr. Green",
				"Tr. Flu. Green",
				"Phosph. White",
				"Light red",
				"Medium red",
				"Medium blue",
				"Light grey",
				"Bright violet",
				"Br. yellowish orange",
				"Bright orange",
				"Bright bluish green",
				"Earth yellow",
				"Bright bluish violet",
				"Tr. Brown",
				"Medium bluish violet",
				"Tr. Medi. reddish violet",
				"Med. yellowish green",
				"Med. bluish green",
				"Light bluish green",
				"Br. yellowish green",
				"Lig. yellowish green",
				"Med. yellowish orange",
				"Br. reddish orange",
				"Bright reddish violet",
				"Light orange",
				"Tr. Bright bluish violet",
				"Gold",
				"Dark nougat",
				"Silver",
				"Neon orange",
				"Neon green",
				"Sand blue",
				"Sand violet",
				"Medium orange",
				"Sand yellow",
				"Earth blue",
				"Earth green",
				"Tr. Flu. Blue",
				"Sand blue metallic",
				"Sand violet metallic",
				"Sand yellow metallic",
				"Dark grey metallic",
				"Black metallic",
				"Light grey metallic",
				"Sand green",
				"Sand red",
				"Dark red",
				"Tr. Flu. Yellow",
				"Tr. Flu. Red",
				"Gun metallic",
				"Red flip/flop",
				"Yellow flip/flop",
				"Silver flip/flop",
				"Curry",
				"Fire Yellow",
				"Flame yellowish orange",
				"Reddish brown",
				"Flame reddish orange",
				"Medium stone grey",
				"Royal blue",
				"Dark Royal blue",
				"Bright reddish lilac",
				"Dark stone grey",
				"Lemon metalic",
				"Light stone grey",
				"Dark Curry",
				"Faded green",
				"Turquoise",
				"Light Royal blue",
				"Medium Royal blue",
				"Brown",
				"Reddish lilac",
				"Lilac",
				"Light lilac",
				"Bright purple",
				"Light purple",
				"Light pink",
				"Light brick yellow",
				"Warm yellowish orange",
				"Cool yellow",
				"Dove blue",
				"Medium lilac",
				"Institutional white",
				"Mid gray",
				"Really black",
				"Really red",
				"Deep orange",
				"Alder",
				"Dusty Rose",
				"Olive",
				"New Yeller",
				"Really blue",
				"Navy blue",
				"Deep blue",
				"Cyan",
				"CGA brown",
				"Magenta",
				"Pink",
				"Teal",
				"Toothpaste",
				"Lime green",
				"Camo",
				"Grime",
				"Lavender",
				"Pastel light blue",
				"Pastel orange",
				"Pastel violet",
				"Pastel blue-green",
				"Pastel green",
				"Pastel yellow",
				"Pastel brown",
				"Royal purple",
				"Hot pink"
			];
			
			$(function(){
				var stringthing = "";
				
				$("tr").each(function() {
					if($(this).find("th").length == 0) {
						if(!colours.includes($($(this).find("td")[1]).html().trim())) {
							//console.log($($(this).find("td")[1]).html());
							$(this).remove();
						} else {
							//stringthing += ("\"#"+$($(this).find("td")[0]).attr("bgcolor")+"\": " + $($(this).find("td")[2]).html()+",")+"\n";
							stringthing += $($(this).find("td")[2]).html()+",\n";
						}
					}
				})

				window.setTimeout(function() {
					console.log(stringthing);
				}, 1500)
			})
			
		</script>
	</head>
	<body>
		<!-- From https://rblxbrickcolor.neocities.org/ -->
		<table class="table">
	<tbody><tr>
		<th width="50">Color</th>
		<th width="200">Name</th>
		<th width="100">Number</th>
		<th width="100">RGB</th>
		<th width="100">Hex</th>
	</tr>
	<tr>
		<td bgcolor="F2F3F3"></td>
		<td>White</td>
		<td>1</td>
		<td>242, 243, 243</td>
		<td>#F2F3F3</td>
	</tr>
	<tr>
		<td bgcolor="A1A5A2"></td>
		<td>Grey</td>
		<td>2</td>
		<td>161, 165, 162</td>
		<td>#A1A5A2</td>
	</tr>
	<tr>
		<td bgcolor="F9E999"></td>
		<td>Light yellow</td>
		<td>3</td>
		<td>249, 233, 153</td>
		<td>#F9E999</td>
	</tr>
	<tr>
		<td bgcolor="D7C59A"></td>
		<td>Brick yellow</td>
		<td>5</td>
		<td>215, 197, 154</td>
		<td>#D7C59A</td>
	</tr>
	<tr>
		<td bgcolor="C2DAB8"></td>
		<td>Light green (Mint)</td>
		<td>6</td>
		<td>194, 218, 184</td>
		<td>#C2DAB8</td>
	</tr>
	<tr>
		<td bgcolor="E8BAC8"></td>
		<td>Light reddish violet</td>
		<td>9</td>
		<td>232, 186, 200</td>
		<td>#E8BAC8</td>
	</tr>
	<tr>
		<td bgcolor="80BBDB"></td>
		<td>Pastel Blue</td>
		<td>11</td>
		<td>128, 187, 219</td>
		<td>#80BBDB</td>
	</tr>
	<tr>
		<td bgcolor="CB8442"></td>
		<td>Light orange brown</td>
		<td>12</td>
		<td>203, 132, 66</td>
		<td>#CB8442</td>
	</tr>
	<tr>
		<td bgcolor="CC8E69"></td>
		<td>Nougat</td>
		<td>18</td>
		<td>204, 142, 105</td>
		<td>#CC8E69</td>
	</tr>
	<tr>
		<td bgcolor="C4281C"></td>
		<td>Bright red</td>
		<td>21</td>
		<td>196, 40, 28</td>
		<td>#C4281C</td>
	</tr>
	<tr>
		<td bgcolor="C470A0"></td>
		<td>Med. reddish violet</td>
		<td>22</td>
		<td>196, 112, 160</td>
		<td>#C470A0</td>
	</tr>
	<tr>
		<td bgcolor="0D69AC"></td>
		<td>Bright blue</td>
		<td>23</td>
		<td>13, 105, 172</td>
		<td>#0D69AC</td>
	</tr>
	<tr>
		<td bgcolor="F5CD30"></td>
		<td>Bright yellow</td>
		<td>24</td>
		<td>245, 205, 48</td>
		<td>#F5CD30</td>
	</tr>
	<tr>
		<td bgcolor="624732"></td>
		<td>Earth orange</td>
		<td>25</td>
		<td>98, 71, 50</td>
		<td>#624732</td>
	</tr>
	<tr>
		<td bgcolor="1B2A35"></td>
		<td>Black</td>
		<td>26</td>
		<td>27, 42, 53</td>
		<td>#1B2A35</td>
	</tr>
	<tr>
		<td bgcolor="6D6E6C"></td>
		<td>Dark grey</td>
		<td>27</td>
		<td>109, 110, 108</td>
		<td>#6D6E6C</td>
	</tr>
	<tr>
		<td bgcolor="287F47"></td>
		<td>Dark green</td>
		<td>28</td>
		<td>40, 127, 71</td>
		<td>#287F47</td>
	</tr>
	<tr>
		<td bgcolor="A1C48C"></td>
		<td>Medium green</td>
		<td>29</td>
		<td>161, 196, 140</td>
		<td>#A1C48C</td>
	</tr>
	<tr>
		<td bgcolor="F3CF9B"></td>
		<td>Lig. Yellowich orange</td>
		<td>36</td>
		<td>243, 207, 155</td>
		<td>#F3CF9B</td>
	</tr>
	<tr>
		<td bgcolor="4B974B"></td>
		<td>Bright green</td>
		<td>37</td>
		<td>75, 151, 75</td>
		<td>#4B974B</td>
	</tr>
	<tr>
		<td bgcolor="A05F35"></td>
		<td>Dark orange</td>
		<td>38</td>
		<td>160, 95, 53</td>
		<td>#A05F35</td>
	</tr>
	<tr>
		<td bgcolor="C1CADE"></td>
		<td>Light bluish violet</td>
		<td>39</td>
		<td>193, 202, 222</td>
		<td>#C1CADE</td>
	</tr>
	<tr>
		<td bgcolor="ECECEC"></td>
		<td>Transparent</td>
		<td>40</td>
		<td>236, 236, 236</td>
		<td>#ECECEC</td>
	</tr>
	<tr>
		<td bgcolor="CD544B"></td>
		<td>Tr. Red</td>
		<td>41</td>
		<td>205, 84, 75</td>
		<td>#CD544B</td>
	</tr>
	<tr>
		<td bgcolor="C1DFF0"></td>
		<td>Tr. Lg blue</td>
		<td>42</td>
		<td>193, 223, 240</td>
		<td>#C1DFF0</td>
	</tr>
	<tr>
		<td bgcolor="7BB6E8"></td>
		<td>Tr. Blue</td>
		<td>43</td>
		<td>123, 182, 232</td>
		<td>#7BB6E8</td>
	</tr>
	<tr>
		<td bgcolor="F7F18D"></td>
		<td>Tr. Yellow</td>
		<td>44</td>
		<td>247, 241, 141</td>
		<td>#F7F18D</td>
	</tr>
	<tr>
		<td bgcolor="B4D2E4"></td>
		<td>Light blue</td>
		<td>45</td>
		<td>180, 210, 228</td>
		<td>#B4D2E4</td>
	</tr>
	<tr>
		<td bgcolor="D9856C"></td>
		<td>Tr. Flu. Reddish orange</td>
		<td>47</td>
		<td>217, 133, 108</td>
		<td>#D9856C</td>
	</tr>
	<tr>
		<td bgcolor="84B68D"></td>
		<td>Tr. Green</td>
		<td>48</td>
		<td>132, 182, 141</td>
		<td>#84B68D</td>
	</tr>
	<tr>
		<td bgcolor="F8F184"></td>
		<td>Tr. Flu. Green</td>
		<td>49</td>
		<td>248, 241, 132</td>
		<td>#F8F184</td>
	</tr>
	<tr>
		<td bgcolor="ECE8DE"></td>
		<td>Phosph. White</td>
		<td>50</td>
		<td>236, 232, 222</td>
		<td>#ECE8DE</td>
	</tr>
	<tr>
		<td bgcolor="EEC4B6"></td>
		<td>Light red</td>
		<td>100</td>
		<td>238, 196, 182</td>
		<td>#EEC4B6</td>
	</tr>
	<tr>
		<td bgcolor="DA867A"></td>
		<td>Medium red</td>
		<td>101</td>
		<td>218, 134, 122</td>
		<td>#DA867A</td>
	</tr>
	<tr>
		<td bgcolor="6E99CA"></td>
		<td>Medium blue</td>
		<td>102</td>
		<td>110, 153, 202</td>
		<td>#6E99CA</td>
	</tr>
	<tr>
		<td bgcolor="C7C1B7"></td>
		<td>Light grey</td>
		<td>103</td>
		<td>199, 193, 183</td>
		<td>#C7C1B7</td>
	</tr>
	<tr>
		<td bgcolor="6B327C"></td>
		<td>Bright violet</td>
		<td>104</td>
		<td>107, 50, 124</td>
		<td>#6B327C</td>
	</tr>
	<tr>
		<td bgcolor="E29B40"></td>
		<td>Br. yellowish orange</td>
		<td>105</td>
		<td>226, 155, 64</td>
		<td>#E29B40</td>
	</tr>
	<tr>
		<td bgcolor="DA8541"></td>
		<td>Bright orange</td>
		<td>106</td>
		<td>218, 133, 65</td>
		<td>#DA8541</td>
	</tr>
	<tr>
		<td bgcolor="008F9C"></td>
		<td>Bright bluish green</td>
		<td>107</td>
		<td>0, 143, 156</td>
		<td>#008F9C</td>
	</tr>
	<tr>
		<td bgcolor="685C43"></td>
		<td>Earth yellow</td>
		<td>108</td>
		<td>104, 92, 67</td>
		<td>#685C43</td>
	</tr>
	<tr>
		<td bgcolor="435493"></td>
		<td>Bright bluish violet</td>
		<td>110</td>
		<td>67, 84, 147</td>
		<td>#435493</td>
	</tr>
	<tr>
		<td bgcolor="BFB7B1"></td>
		<td>Tr. Brown</td>
		<td>111</td>
		<td>191, 183, 177</td>
		<td>#BFB7B1</td>
	</tr>
	<tr>
		<td bgcolor="6874AC"></td>
		<td>Medium bluish violet</td>
		<td>112</td>
		<td>104, 116, 172</td>
		<td>#6874AC</td>
	</tr>
	<tr>
		<td bgcolor="E5ADC8"></td>
		<td>Tr. Medi. reddish violet</td>
		<td>113</td>
		<td>229, 173, 200</td>
		<td>#E5ADC8</td>
	</tr>
	<tr>
		<td bgcolor="C7D23C"></td>
		<td>Med. yellowish green</td>
		<td>115</td>
		<td>199, 210, 60</td>
		<td>#C7D23C</td>
	</tr>
	<tr>
		<td bgcolor="55A5AF"></td>
		<td>Med. bluish green</td>
		<td>116</td>
		<td>85, 165, 175</td>
		<td>#55A5AF</td>
	</tr>
	<tr>
		<td bgcolor="B7D7D5"></td>
		<td>Light bluish green</td>
		<td>118</td>
		<td>183, 215, 213</td>
		<td>#B7D7D5</td>
	</tr>
	<tr>
		<td bgcolor="A4BD47"></td>
		<td>Br. yellowish green</td>
		<td>119</td>
		<td>164, 189, 71</td>
		<td>#A4BD47</td>
	</tr>
	<tr>
		<td bgcolor="D9E4A7"></td>
		<td>Lig. yellowish green</td>
		<td>120</td>
		<td>217, 228, 167</td>
		<td>#D9E4A7</td>
	</tr>
	<tr>
		<td bgcolor="E7AC58"></td>
		<td>Med. yellowish orange</td>
		<td>121</td>
		<td>231, 172, 88</td>
		<td>#E7AC58</td>
	</tr>
	<tr>
		<td bgcolor="D36F4C"></td>
		<td>Br. reddish orange</td>
		<td>123</td>
		<td>211, 111, 76</td>
		<td>#D36F4C</td>
	</tr>
	<tr>
		<td bgcolor="923978"></td>
		<td>Bright reddish violet</td>
		<td>124</td>
		<td>146, 57, 120</td>
		<td>#923978</td>
	</tr>
	<tr>
		<td bgcolor="EAB892"></td>
		<td>Light orange</td>
		<td>125</td>
		<td>234, 184, 146</td>
		<td>#EAB892</td>
	</tr>
	<tr>
		<td bgcolor="A5A5CB"></td>
		<td>Tr. Bright bluish violet</td>
		<td>126</td>
		<td>165, 165, 203</td>
		<td>#A5A5CB</td>
	</tr>
	<tr>
		<td bgcolor="DCBC81"></td>
		<td>Gold</td>
		<td>127</td>
		<td>220, 188, 129</td>
		<td>#DCBC81</td>
	</tr>
	<tr>
		<td bgcolor="AE7A59"></td>
		<td>Dark nougat</td>
		<td>128</td>
		<td>174, 122, 89</td>
		<td>#AE7A59</td>
	</tr>
	<tr>
		<td bgcolor="9CA3A8"></td>
		<td>Silver</td>
		<td>131</td>
		<td>156, 163, 168</td>
		<td>#9CA3A8</td>
	</tr>
	<tr>
		<td bgcolor="D5733D"></td>
		<td>Neon orange</td>
		<td>133</td>
		<td>213, 115, 61</td>
		<td>#D5733D</td>
	</tr>
	<tr>
		<td bgcolor="D8DD56"></td>
		<td>Neon green</td>
		<td>134</td>
		<td>216, 221, 86</td>
		<td>#D8DD56</td>
	</tr>
	<tr>
		<td bgcolor="74869D"></td>
		<td>Sand blue</td>
		<td>135</td>
		<td>116, 134, 157</td>
		<td>#74869D</td>
	</tr>
	<tr>
		<td bgcolor="877C90"></td>
		<td>Sand violet</td>
		<td>136</td>
		<td>135, 124, 144</td>
		<td>#877C90</td>
	</tr>
	<tr>
		<td bgcolor="E09864"></td>
		<td>Medium orange</td>
		<td>137</td>
		<td>224, 152, 100</td>
		<td>#E09864</td>
	</tr>
	<tr>
		<td bgcolor="958A73"></td>
		<td>Sand yellow</td>
		<td>138</td>
		<td>149, 138, 115</td>
		<td>#958A73</td>
	</tr>
	<tr>
		<td bgcolor="203A56"></td>
		<td>Earth blue</td>
		<td>140</td>
		<td>32, 58, 86</td>
		<td>#203A56</td>
	</tr>
	<tr>
		<td bgcolor="27462D"></td>
		<td>Earth green</td>
		<td>141</td>
		<td>39, 70, 45</td>
		<td>#27462D</td>
	</tr>
	<tr>
		<td bgcolor="CFE2F7"></td>
		<td>Tr. Flu. Blue</td>
		<td>143</td>
		<td>207, 226, 247</td>
		<td>#CFE2F7</td>
	</tr>
	<tr>
		<td bgcolor="7988A1"></td>
		<td>Sand blue metallic</td>
		<td>145</td>
		<td>121, 136, 161</td>
		<td>#7988A1</td>
	</tr>
	<tr>
		<td bgcolor="958EA3"></td>
		<td>Sand violet metallic</td>
		<td>146</td>
		<td>149, 142, 163</td>
		<td>#958EA3</td>
	</tr>
	<tr>
		<td bgcolor="938767"></td>
		<td>Sand yellow metallic</td>
		<td>147</td>
		<td>147, 135, 103</td>
		<td>#938767</td>
	</tr>
	<tr>
		<td bgcolor="575857"></td>
		<td>Dark grey metallic</td>
		<td>148</td>
		<td>87, 88, 87</td>
		<td>#575857</td>
	</tr>
	<tr>
		<td bgcolor="161D32"></td>
		<td>Black metallic</td>
		<td>149</td>
		<td>22, 29, 50</td>
		<td>#161D32</td>
	</tr>
	<tr>
		<td bgcolor="ABADAC"></td>
		<td>Light grey metallic</td>
		<td>150</td>
		<td>171, 173, 172</td>
		<td>#ABADAC</td>
	</tr>
	<tr>
		<td bgcolor="789082"></td>
		<td>Sand green</td>
		<td>151</td>
		<td>120, 144, 130</td>
		<td>#789082</td>
	</tr>
	<tr>
		<td bgcolor="957977"></td>
		<td>Sand red</td>
		<td>153</td>
		<td>149, 121, 119</td>
		<td>#957977</td>
	</tr>
	<tr>
		<td bgcolor="7B2E2F"></td>
		<td>Dark red</td>
		<td>154</td>
		<td>123, 46, 47</td>
		<td>#7B2E2F</td>
	</tr>
	<tr>
		<td bgcolor="FFF67B"></td>
		<td>Tr. Flu. Yellow</td>
		<td>157</td>
		<td>255, 246, 123</td>
		<td>#FFF67B</td>
	</tr>
	<tr>
		<td bgcolor="E1A4C2"></td>
		<td>Tr. Flu. Red</td>
		<td>158</td>
		<td>225, 164, 194</td>
		<td>#E1A4C2</td>
	</tr>
	<tr>
		<td bgcolor="756C62"></td>
		<td>Gun metallic</td>
		<td>168</td>
		<td>117, 108, 98</td>
		<td>#756C62</td>
	</tr>
	<tr>
		<td bgcolor="97695B"></td>
		<td>Red flip/flop</td>
		<td>176</td>
		<td>151, 105, 91</td>
		<td>#97695B</td>
	</tr>
	<tr>
		<td bgcolor="B48455"></td>
		<td>Yellow flip/flop</td>
		<td>178</td>
		<td>180, 132, 85</td>
		<td>#B48455</td>
	</tr>
	<tr>
		<td bgcolor="898788"></td>
		<td>Silver flip/flop</td>
		<td>179</td>
		<td>137, 135, 136</td>
		<td>#898788</td>
	</tr>
	<tr>
		<td bgcolor="D7A94B"></td>
		<td>Curry</td>
		<td>180</td>
		<td>215, 169, 75</td>
		<td>#D7A94B</td>
	</tr>
	<tr>
		<td bgcolor="F9D62E"></td>
		<td>Fire Yellow</td>
		<td>190</td>
		<td>249, 214, 46</td>
		<td>#F9D62E</td>
	</tr>
	<tr>
		<td bgcolor="E8AB2D"></td>
		<td>Flame yellowish orange</td>
		<td>191</td>
		<td>232, 171, 45</td>
		<td>#E8AB2D</td>
	</tr>
	<tr>
		<td bgcolor="694028"></td>
		<td>Reddish brown</td>
		<td>192</td>
		<td>105, 64, 40</td>
		<td>#694028</td>
	</tr>
	<tr>
		<td bgcolor="CF6024"></td>
		<td>Flame reddish orange</td>
		<td>193</td>
		<td>207, 96, 36</td>
		<td>#CF6024</td>
	</tr>
	<tr>
		<td bgcolor="A3A2A5"></td>
		<td>Medium stone grey</td>
		<td>194</td>
		<td>163, 162, 165</td>
		<td>#A3A2A5</td>
	</tr>
	<tr>
		<td bgcolor="4667A4"></td>
		<td>Royal blue</td>
		<td>195</td>
		<td>70, 103, 164</td>
		<td>#4667A4</td>
	</tr>
	<tr>
		<td bgcolor="23478B"></td>
		<td>Dark Royal blue</td>
		<td>196</td>
		<td>35, 71, 139</td>
		<td>#23478B</td>
	</tr>
	<tr>
		<td bgcolor="8E4285"></td>
		<td>Bright reddish lilac</td>
		<td>198</td>
		<td>142, 66, 133</td>
		<td>#8E4285</td>
	</tr>
	<tr>
		<td bgcolor="635F62"></td>
		<td>Dark stone grey</td>
		<td>199</td>
		<td>99, 95, 98</td>
		<td>#635F62</td>
	</tr>
	<tr>
		<td bgcolor="828A5D"></td>
		<td>Lemon metalic</td>
		<td>200</td>
		<td>130, 138, 93</td>
		<td>#828A5D</td>
	</tr>
	<tr>
		<td bgcolor="E5E4DF"></td>
		<td>Light stone grey</td>
		<td>208</td>
		<td>229, 228, 223</td>
		<td>#E5E4DF</td>
	</tr>
	<tr>
		<td bgcolor="B08E44"></td>
		<td>Dark Curry</td>
		<td>209</td>
		<td>176, 142, 68</td>
		<td>#B08E44</td>
	</tr>
	<tr>
		<td bgcolor="709578"></td>
		<td>Faded green</td>
		<td>210</td>
		<td>112, 149, 120</td>
		<td>#709578</td>
	</tr>
	<tr>
		<td bgcolor="79B5B5"></td>
		<td>Turquoise</td>
		<td>211</td>
		<td>121, 181, 181</td>
		<td>#79B5B5</td>
	</tr>
	<tr>
		<td bgcolor="9FC3E9"></td>
		<td>Light Royal blue</td>
		<td>212</td>
		<td>159, 195, 233</td>
		<td>#9FC3E9</td>
	</tr>
	<tr>
		<td bgcolor="6C81B7"></td>
		<td>Medium Royal blue</td>
		<td>213</td>
		<td>108, 129, 183</td>
		<td>#6C81B7</td>
	</tr>
	<tr>
		<td bgcolor="904C2A"></td>
		<td>Rust</td>
		<td>216</td>
		<td>144, 76, 42</td>
		<td>#904C2A</td>
	</tr>
	<tr>
		<td bgcolor="7C5C46"></td>
		<td>Brown</td>
		<td>217</td>
		<td>124, 92, 70</td>
		<td>#7C5C46</td>
	</tr>
	<tr>
		<td bgcolor="96709F"></td>
		<td>Reddish lilac</td>
		<td>218</td>
		<td>150, 112, 159</td>
		<td>#96709F</td>
	</tr>
	<tr>
		<td bgcolor="6B629B"></td>
		<td>Lilac</td>
		<td>219</td>
		<td>107, 98, 155</td>
		<td>#6B629B</td>
	</tr>
	<tr>
		<td bgcolor="A7A9CE"></td>
		<td>Light lilac</td>
		<td>220</td>
		<td>167, 169, 206</td>
		<td>#A7A9CE</td>
	</tr>
	<tr>
		<td bgcolor="CD6298"></td>
		<td>Bright purple</td>
		<td>221</td>
		<td>205, 98, 152</td>
		<td>#CD6298</td>
	</tr>
	<tr>
		<td bgcolor="E4ADC8"></td>
		<td>Light purple</td>
		<td>222</td>
		<td>228, 173, 200</td>
		<td>#E4ADC8</td>
	</tr>
	<tr>
		<td bgcolor="DC9095"></td>
		<td>Light pink</td>
		<td>223</td>
		<td>220, 144, 149</td>
		<td>#DC9095</td>
	</tr>
	<tr>
		<td bgcolor="F0D5A0"></td>
		<td>Light brick yellow</td>
		<td>224</td>
		<td>240, 213, 160</td>
		<td>#F0D5A0</td>
	</tr>
	<tr>
		<td bgcolor="EBB87F"></td>
		<td>Warm yellowish orange</td>
		<td>225</td>
		<td>235, 184, 127</td>
		<td>#EBB87F</td>
	</tr>
	<tr>
		<td bgcolor="FDEA8D"></td>
		<td>Cool yellow</td>
		<td>226</td>
		<td>253, 234, 141</td>
		<td>#FDEA8D</td>
	</tr>
	<tr>
		<td bgcolor="7DBBDD"></td>
		<td>Dove blue</td>
		<td>232</td>
		<td>125, 187, 221</td>
		<td>#7DBBDD</td>
	</tr>
	<tr>
		<td bgcolor="342B75"></td>
		<td>Medium lilac</td>
		<td>268</td>
		<td>52, 43, 117</td>
		<td>#342B75</td>
	</tr>
	<tr>
		<td bgcolor="506D54"></td>
		<td>Slime green</td>
		<td>301</td>
		<td>80, 109, 84</td>
		<td>#506D54</td>
	</tr>
	<tr>
		<td bgcolor="5B5D69"></td>
		<td>Smoky grey</td>
		<td>302</td>
		<td>91, 93, 105</td>
		<td>#5B5D69</td>
	</tr>
	<tr>
		<td bgcolor="0010B0"></td>
		<td>Dark blue</td>
		<td>303</td>
		<td>0, 16, 176</td>
		<td>#0010B0</td>
	</tr>
	<tr>
		<td bgcolor="2C651D"></td>
		<td>Parsley green</td>
		<td>304</td>
		<td>44, 101, 29</td>
		<td>#2C651D</td>
	</tr>
	<tr>
		<td bgcolor="527CAE"></td>
		<td>Steel blue</td>
		<td>305</td>
		<td>82, 124, 174</td>
		<td>#527CAE</td>
	</tr>
	<tr>
		<td bgcolor="335882"></td>
		<td>Storm blue</td>
		<td>306</td>
		<td>51, 88, 130</td>
		<td>#335882</td>
	</tr>
	<tr>
		<td bgcolor="102ADC"></td>
		<td>Lapis</td>
		<td>307</td>
		<td>16, 42, 220</td>
		<td>#102ADC</td>
	</tr>
	<tr>
		<td bgcolor="3D1585"></td>
		<td>Dark indigo</td>
		<td>308</td>
		<td>61, 21, 133</td>
		<td>#3D1585</td>
	</tr>
	<tr>
		<td bgcolor="348E40"></td>
		<td>Sea green</td>
		<td>309</td>
		<td>52, 142, 64</td>
		<td>#348E40</td>
	</tr>
	<tr>
		<td bgcolor="5B9A4C"></td>
		<td>Shamrock</td>
		<td>310</td>
		<td>91, 154, 76</td>
		<td>#5B9A4C</td>
	</tr>
	<tr>
		<td bgcolor="9FA1AC"></td>
		<td>Fossil</td>
		<td>311</td>
		<td>159, 161, 172</td>
		<td>#9FA1AC</td>
	</tr>
	<tr>
		<td bgcolor="592259"></td>
		<td>Mulberry</td>
		<td>312</td>
		<td>89, 34, 89</td>
		<td>#592259</td>
	</tr>
	<tr>
		<td bgcolor="1F801D"></td>
		<td>Forest green</td>
		<td>313</td>
		<td>31, 128, 29</td>
		<td>#1F801D</td>
	</tr>
	<tr>
		<td bgcolor="9FADC0"></td>
		<td>Cadet blue</td>
		<td>314</td>
		<td>159, 173, 192</td>
		<td>#9FADC0</td>
	</tr>
	<tr>
		<td bgcolor="0989CF"></td>
		<td>Electric blue</td>
		<td>315</td>
		<td>9, 137, 207</td>
		<td>#0989CF</td>
	</tr>
	<tr>
		<td bgcolor="7B007B"></td>
		<td>Eggplant</td>
		<td>316</td>
		<td>123, 0, 123</td>
		<td>#7B007B</td>
	</tr>
	<tr>
		<td bgcolor="7C9C6B"></td>
		<td>Moss</td>
		<td>317</td>
		<td>124, 156, 107</td>
		<td>#7C9C6B</td>
	</tr>
	<tr>
		<td bgcolor="8AAB85"></td>
		<td>Artichoke</td>
		<td>318</td>
		<td>138, 171, 133</td>
		<td>#8AAB85</td>
	</tr>
	<tr>
		<td bgcolor="B9C4B1"></td>
		<td>Sage green</td>
		<td>319</td>
		<td>185, 196, 177</td>
		<td>#B9C4B1</td>
	</tr>
	<tr>
		<td bgcolor="CACBD1"></td>
		<td>Ghost grey</td>
		<td>320</td>
		<td>202, 203, 209</td>
		<td>#CACBD1</td>
	</tr>
	<tr>
		<td bgcolor="A75E9B"></td>
		<td>Lilac</td>
		<td>321</td>
		<td>167, 94, 155</td>
		<td>#A75E9B</td>
	</tr>
	<tr>
		<td bgcolor="7B2F7B"></td>
		<td>Plum</td>
		<td>322</td>
		<td>123, 47, 123</td>
		<td>#7B2F7B</td>
	</tr>
	<tr>
		<td bgcolor="94BE81"></td>
		<td>Olivine</td>
		<td>323</td>
		<td>148, 190, 129</td>
		<td>#94BE81</td>
	</tr>
	<tr>
		<td bgcolor="A8BD99"></td>
		<td>Laurel green</td>
		<td>324</td>
		<td>168, 189, 153</td>
		<td>#A8BD99</td>
	</tr>
	<tr>
		<td bgcolor="DFDFDE"></td>
		<td>Quill grey</td>
		<td>325</td>
		<td>223, 223, 222</td>
		<td>#DFDFDE</td>
	</tr>
	<tr>
		<td bgcolor="970000"></td>
		<td>Crimson</td>
		<td>327</td>
		<td>151, 0, 0</td>
		<td>#970000</td>
	</tr>
	<tr>
		<td bgcolor="B1E5A6"></td>
		<td>Mint</td>
		<td>328</td>
		<td>177, 229, 166</td>
		<td>#B1E5A6</td>
	</tr>
	<tr>
		<td bgcolor="98C2DB"></td>
		<td>Baby blue</td>
		<td>329</td>
		<td>152, 194, 219</td>
		<td>#98C2DB</td>
	</tr>
	<tr>
		<td bgcolor="FF98DC"></td>
		<td>Carnation pink</td>
		<td>330</td>
		<td>255, 152, 220</td>
		<td>#FF98DC</td>
	</tr>
	<tr>
		<td bgcolor="FF5959"></td>
		<td>Persimmon</td>
		<td>331</td>
		<td>255, 89, 89</td>
		<td>#FF5959</td>
	</tr>
	<tr>
		<td bgcolor="750000"></td>
		<td>Maroon</td>
		<td>332</td>
		<td>117, 0, 0</td>
		<td>#750000</td>
	</tr>
	<tr>
		<td bgcolor="EFB838"></td>
		<td>Gold</td>
		<td>333</td>
		<td>239, 184, 56</td>
		<td>#EFB838</td>
	</tr>
	<tr>
		<td bgcolor="F8D96D"></td>
		<td>Daisy orange</td>
		<td>334</td>
		<td>248, 217, 109</td>
		<td>#F8D96D</td>
	</tr>
	<tr>
		<td bgcolor="E7E7EC"></td>
		<td>Pearl</td>
		<td>335</td>
		<td>231, 231, 236</td>
		<td>#E7E7EC</td>
	</tr>
	<tr>
		<td bgcolor="C7D4E4"></td>
		<td>Fog</td>
		<td>336</td>
		<td>199, 212, 228</td>
		<td>#C7D4E4</td>
	</tr>
	<tr>
		<td bgcolor="FF9494"></td>
		<td>Salmon</td>
		<td>337</td>
		<td>255, 148, 148</td>
		<td>#FF9494</td>
	</tr>
	<tr>
		<td bgcolor="BE6862"></td>
		<td>Terra Cotta</td>
		<td>338</td>
		<td>190, 104, 98</td>
		<td>#BE6862</td>
	</tr>
	<tr>
		<td bgcolor="562424"></td>
		<td>Cocoa</td>
		<td>339</td>
		<td>86, 36, 36</td>
		<td>#562424</td>
	</tr>
	<tr>
		<td bgcolor="F1E7C7"></td>
		<td>Wheat</td>
		<td>340</td>
		<td>241, 231, 199</td>
		<td>#F1E7C7</td>
	</tr>
	<tr>
		<td bgcolor="FEF3BB"></td>
		<td>Buttermilk</td>
		<td>341</td>
		<td>254, 243, 187</td>
		<td>#FEF3BB</td>
	</tr>
	<tr>
		<td bgcolor="E0B2D0"></td>
		<td>Mauve</td>
		<td>342</td>
		<td>224, 178, 208</td>
		<td>#E0B2D0</td>
	</tr>
	<tr>
		<td bgcolor="D490BD"></td>
		<td>Sunrise</td>
		<td>343</td>
		<td>212, 144, 189</td>
		<td>#D490BD</td>
	</tr>
	<tr>
		<td bgcolor="965555"></td>
		<td>Tawny</td>
		<td>344</td>
		<td>150, 85, 85</td>
		<td>#965555</td>
	</tr>
	<tr>
		<td bgcolor="8F4C2A"></td>
		<td>Rust</td>
		<td>345</td>
		<td>143, 76, 42</td>
		<td>#8F4C2A</td>
	</tr>
	<tr>
		<td bgcolor="D3BE96"></td>
		<td>Cashmere</td>
		<td>346</td>
		<td>211, 190, 150</td>
		<td>#D3BE96</td>
	</tr>
	<tr>
		<td bgcolor="E2DCBC"></td>
		<td>Khaki</td>
		<td>347</td>
		<td>226, 220, 188</td>
		<td>#E2DCBC</td>
	</tr>
	<tr>
		<td bgcolor="EDEAEA"></td>
		<td>Lily white</td>
		<td>348</td>
		<td>237, 234, 234</td>
		<td>#EDEAEA</td>
	</tr>
	<tr>
		<td bgcolor="E9DADA"></td>
		<td>Seashell</td>
		<td>349</td>
		<td>233, 218, 218</td>
		<td>#E9DADA</td>
	</tr>
	<tr>
		<td bgcolor="883E3E"></td>
		<td>Burgundy</td>
		<td>350</td>
		<td>136, 62, 62</td>
		<td>#883E3E</td>
	</tr>
	<tr>
		<td bgcolor="BC9B5D"></td>
		<td>Cork</td>
		<td>351</td>
		<td>188, 155, 93</td>
		<td>#BC9B5D</td>
	</tr>
	<tr>
		<td bgcolor="C7AC78"></td>
		<td>Burlap</td>
		<td>352</td>
		<td>199, 172, 120</td>
		<td>#C7AC78</td>
	</tr>
	<tr>
		<td bgcolor="CABFA3"></td>
		<td>Beige</td>
		<td>353</td>
		<td>202, 191, 163</td>
		<td>#CABFA3</td>
	</tr>
	<tr>
		<td bgcolor="BBB3B2"></td>
		<td>Oyster</td>
		<td>354</td>
		<td>187, 179, 178</td>
		<td>#BBB3B2</td>
	</tr>
	<tr>
		<td bgcolor="6C584B"></td>
		<td>Pine Cone</td>
		<td>355</td>
		<td>108, 88, 75</td>
		<td>#6C584B</td>
	</tr>
	<tr>
		<td bgcolor="A0844F"></td>
		<td>Fawn brown</td>
		<td>356</td>
		<td>160, 132, 79</td>
		<td>#A0844F</td>
	</tr>
	<tr>
		<td bgcolor="958988"></td>
		<td>Hurricane grey</td>
		<td>357</td>
		<td>149, 137, 136</td>
		<td>#958988</td>
	</tr>
	<tr>
		<td bgcolor="ABA89E"></td>
		<td>Cloudy grey</td>
		<td>358</td>
		<td>171, 168, 158</td>
		<td>#ABA89E</td>
	</tr>
	<tr>
		<td bgcolor="AF9483"></td>
		<td>Linen</td>
		<td>359</td>
		<td>175, 148, 131</td>
		<td>#AF9483</td>
	</tr>
	<tr>
		<td bgcolor="966766"></td>
		<td>Copper</td>
		<td>360</td>
		<td>150, 103, 102</td>
		<td>#966766</td>
	</tr>
	<tr>
		<td bgcolor="564236"></td>
		<td>Dirt brown</td>
		<td>361</td>
		<td>86, 66, 54</td>
		<td>#564236</td>
	</tr>
	<tr>
		<td bgcolor="7E683F"></td>
		<td>Bronze</td>
		<td>362</td>
		<td>126, 104, 63</td>
		<td>#7E683F</td>
	</tr>
	<tr>
		<td bgcolor="69665C"></td>
		<td>Flint</td>
		<td>363</td>
		<td>105, 102, 92</td>
		<td>#69665C</td>
	</tr>
	<tr>
		<td bgcolor="5A4C42"></td>
		<td>Dark taupe</td>
		<td>364</td>
		<td>90, 76, 66</td>
		<td>#5A4C42</td>
	</tr>
	<tr>
		<td bgcolor="6A3909"></td>
		<td>Burnt Sienna</td>
		<td>365</td>
		<td>106, 57, 9</td>
		<td>#6A3909</td>
	</tr>
	<tr>
		<td bgcolor="F8F8F8"></td>
		<td>Institutional white</td>
		<td>1001</td>
		<td>248, 248, 248</td>
		<td>#F8F8F8</td>
	</tr>
	<tr>
		<td bgcolor="CDCDCD"></td>
		<td>Mid gray</td>
		<td>1002</td>
		<td>205, 205, 205</td>
		<td>#CDCDCD</td>
	</tr>
	<tr>
		<td bgcolor="111111"></td>
		<td>Really black</td>
		<td>1003</td>
		<td>17, 17, 17</td>
		<td>#111111</td>
	</tr>
	<tr>
		<td bgcolor="FF0000"></td>
		<td>Really red</td>
		<td>1004</td>
		<td>255, 0, 0</td>
		<td>#FF0000</td>
	</tr>
	<tr>
		<td bgcolor="FFB000"></td>
		<td>Deep orange</td>
		<td>1005</td>
		<td>255, 176, 0</td>
		<td>#FFB000</td>
	</tr>
	<tr>
		<td bgcolor="B480FF"></td>
		<td>Alder</td>
		<td>1006</td>
		<td>180, 128, 255</td>
		<td>#B480FF</td>
	</tr>
	<tr>
		<td bgcolor="A34B4B"></td>
		<td>Dusty Rose</td>
		<td>1007</td>
		<td>163, 75, 75</td>
		<td>#A34B4B</td>
	</tr>
	<tr>
		<td bgcolor="C1BE42"></td>
		<td>Olive</td>
		<td>1008</td>
		<td>193, 190, 66</td>
		<td>#C1BE42</td>
	</tr>
	<tr>
		<td bgcolor="FFFF00"></td>
		<td>New Yeller</td>
		<td>1009</td>
		<td>255, 255, 0</td>
		<td>#FFFF00</td>
	</tr>
	<tr>
		<td bgcolor="0000FF"></td>
		<td>Really blue</td>
		<td>1010</td>
		<td>0, 0, 255</td>
		<td>#0000FF</td>
	</tr>
	<tr>
		<td bgcolor="002060"></td>
		<td>Navy blue</td>
		<td>1011</td>
		<td>0, 32, 96</td>
		<td>#002060</td>
	</tr>
	<tr>
		<td bgcolor="2154B9"></td>
		<td>Deep blue</td>
		<td>1012</td>
		<td>33, 84, 185</td>
		<td>#2154B9</td>
	</tr>
	<tr>
		<td bgcolor="04AFEC"></td>
		<td>Cyan</td>
		<td>1013</td>
		<td>4, 175, 236</td>
		<td>#04AFEC</td>
	</tr>
	<tr>
		<td bgcolor="AA5500"></td>
		<td>CGA brown</td>
		<td>1014</td>
		<td>170, 85, 0</td>
		<td>#AA5500</td>
	</tr>
	<tr>
		<td bgcolor="AA00AA"></td>
		<td>Magenta</td>
		<td>1015</td>
		<td>170, 0, 170</td>
		<td>#AA00AA</td>
	</tr>
	<tr>
		<td bgcolor="FF66CC"></td>
		<td>Pink</td>
		<td>1016</td>
		<td>255, 102, 204</td>
		<td>#FF66CC</td>
	</tr>
	<tr>
		<td bgcolor="FFAF00"></td>
		<td>Deep orange</td>
		<td>1017</td>
		<td>255, 175, 0</td>
		<td>#FFAF00</td>
	</tr>
	<tr>
		<td bgcolor="12EED4"></td>
		<td>Teal</td>
		<td>1018</td>
		<td>18, 238, 212</td>
		<td>#12EED4</td>
	</tr>
	<tr>
		<td bgcolor="00FFFF"></td>
		<td>Toothpaste</td>
		<td>1019</td>
		<td>0, 255, 255</td>
		<td>#00FFFF</td>
	</tr>
	<tr>
		<td bgcolor="00FF00"></td>
		<td>Lime green</td>
		<td>1020</td>
		<td>0, 255, 0</td>
		<td>#00FF00</td>
	</tr>
	<tr>
		<td bgcolor="3A7D15"></td>
		<td>Camo</td>
		<td>1021</td>
		<td>58, 125, 21</td>
		<td>#3A7D15</td>
	</tr>
	<tr>
		<td bgcolor="7F8E64"></td>
		<td>Grime</td>
		<td>1022</td>
		<td>127, 142, 100</td>
		<td>#7F8E64</td>
	</tr>
	<tr>
		<td bgcolor="8C5B9F"></td>
		<td>Lavender</td>
		<td>1023</td>
		<td>140, 91, 159</td>
		<td>#8C5B9F</td>
	</tr>
	<tr>
		<td bgcolor="AFDDFF"></td>
		<td>Pastel light blue</td>
		<td>1024</td>
		<td>175, 221, 255</td>
		<td>#AFDDFF</td>
	</tr>
	<tr>
		<td bgcolor="FFC9C9"></td>
		<td>Pastel orange</td>
		<td>1025</td>
		<td>255, 201, 201</td>
		<td>#FFC9C9</td>
	</tr>
	<tr>
		<td bgcolor="B1A7FF"></td>
		<td>Pastel violet</td>
		<td>1026</td>
		<td>177, 167, 255</td>
		<td>#B1A7FF</td>
	</tr>
	<tr>
		<td bgcolor="9FF3E9"></td>
		<td>Pastel blue-green</td>
		<td>1027</td>
		<td>159, 243, 233</td>
		<td>#9FF3E9</td>
	</tr>
	<tr>
		<td bgcolor="CCFFCC"></td>
		<td>Pastel green</td>
		<td>1028</td>
		<td>204, 255, 204</td>
		<td>#CCFFCC</td>
	</tr>
	<tr>
		<td bgcolor="FFFFCC"></td>
		<td>Pastel yellow</td>
		<td>1029</td>
		<td>255, 255, 204</td>
		<td>#FFFFCC</td>
	</tr>
	<tr>
		<td bgcolor="FFCC99"></td>
		<td>Pastel brown</td>
		<td>1030</td>
		<td>255, 204, 153</td>
		<td>#FFCC99</td>
	</tr>
	<tr>
		<td bgcolor="6225D1"></td>
		<td>Royal purple</td>
		<td>1031</td>
		<td>98, 37, 209</td>
		<td>#6225D1</td>
	</tr>
	<tr>
		<td bgcolor="FF00BF"></td>
		<td>Hot pink</td>
		<td>1032</td>
		<td>255, 0, 191</td>
		<td>#FF00BF</td>
	</tr>
</tbody></table>
	</body>
</html>