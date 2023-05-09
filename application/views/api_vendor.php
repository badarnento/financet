<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Vendor</title>
	<link rel="stylesheet" href="">
	<style>
		.group{
			border-bottom: 2px solid #6e6f6f;
			margin-bottom: 30px;
		}
		p {
			margin: 10px 0;
		}
	</style>
</head>
<body>
	<div style="width: 96%;margin:auto;">
		<div class="main">
			<h2><strong>API Vendor Financetools</strong></h2>
			
		<ul>
			<li><p><strong>URL/Endpoint</strong></p>
			<p><?= (isset($endpoin)) ? $endpoint : 'https://dev.financetools.id/api/vendor'; ?></p>
			</li>
		</ul>
		</div>
		<div class="group">
			
			<h3><strong>Get All Vendor</strong></h3>
			<ul>
			<li><p><strong>Method:</strong></p>
			<p><code>GET</code></p>
			</li>
			<li><p><strong>URL Params</strong></p>
			<p><strong>Required:</strong></p>
			<p><code>id=[integer]</code></p>
			</li>
			<li><p><strong>Data Params</strong></p>
			<p>None</p>
			</li>
			<li><p><strong>Success Response:</strong></p>
			<ul>
			<li><strong>Code:</strong> 200 <br />
			<strong>Content:</strong> <code>{ id : 12, name : &quot;Michael Bloom&quot; }</code></li>
			</ul>
			</li>
			<li><p><strong>Error Response:</strong></p>
			<ul>
			<li><strong>Code:</strong> 404 NOT FOUND <br />
			<strong>Content:</strong> <code>{ error : &quot;User doesn&#39;t exist&quot; }</code></li>
			</ul>
			<p>OR</p>
			<ul>
			<li><strong>Code:</strong> 401 UNAUTHORIZED <br />
			<strong>Content:</strong> <code>{ error : &quot;You are unauthorized to make this request.&quot; }</code></li>
			</ul>
			</li>
			<li><p><strong>Sample Call:</strong></p>
			<pre><code class="lang-javascript">  $.ajax({
			    url: <span class="hljs-string">"/users/1"</span>,
			    dataType: <span class="hljs-string">"json"</span>,
			    type : <span class="hljs-string">"<span class="hljs-keyword">GET</span>"</span>,
			    success : function(r) {
			      console.log(r);
			    }
			  });
			</code></pre>
			</li>
			</ul>
		</div>
		<div class="group">
			
			<h2><strong>Get Single Vendor</strong></h2>
			<p>  Returns json data about a single user.</p>
			<ul>
			<li><p><strong>URL/Endpoint</strong></p>
			<p><?= (isset($endpoin)) ? $endpoint : 'https://dev.financetools.id/api/vendor'; ?></p>
			</li>
			<li><p><strong>Method:</strong></p>
			<p><code>GET</code></p>
			</li>
			<li><p><strong>URL Params</strong></p>
			<p><strong>Required:</strong></p>
			<p><code>id=[integer]</code></p>
			</li>
			<li><p><strong>Data Params</strong></p>
			<p>None</p>
			</li>
			<li><p><strong>Success Response:</strong></p>
			<ul>
			<li><strong>Code:</strong> 200 <br />
			<strong>Content:</strong> <code>{ id : 12, name : &quot;Michael Bloom&quot; }</code></li>
			</ul>
			</li>
			<li><p><strong>Error Response:</strong></p>
			<ul>
			<li><strong>Code:</strong> 404 NOT FOUND <br />
			<strong>Content:</strong> <code>{ error : &quot;User doesn&#39;t exist&quot; }</code></li>
			</ul>
			<p>OR</p>
			<ul>
			<li><strong>Code:</strong> 401 UNAUTHORIZED <br />
			<strong>Content:</strong> <code>{ error : &quot;You are unauthorized to make this request.&quot; }</code></li>
			</ul>
			</li>
			<li><p><strong>Sample Call:</strong></p>
			<pre><code class="lang-javascript">  $.ajax({
			    url: <span class="hljs-string">"/users/1"</span>,
			    dataType: <span class="hljs-string">"json"</span>,
			    type : <span class="hljs-string">"<span class="hljs-keyword">GET</span>"</span>,
			    success : function(r) {
			      console.log(r);
			    }
			  });
			</code></pre>
			</li>
			</ul>
		</div>

		
	</div>
</body>
</html>