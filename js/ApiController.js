/**
 * @author Roman Zaytsev
 */
var user = {
	account: {
		create: function(q) {
			var set = {
				url: "/user/accountCreate",
				data: q.data,
				method:'POST',
				dataType:'json',
				context: q.context,
				success: function(data, textStatus, XHR) {
					if(XHR.status == 201) {
						if(q.success) q.success(data, textStatus, XHR);
						if(q.page_return) window.location=q.page_return;
					}
					else this.error(XHR, data.textStatus, null);
				},
				error: function(XHR, textStatus, errorThrown) {
					if(q.error) q.error(XHR, textStatus, errorThrown);
				}
			};
			if(!set.data) set.data = {};
			set.data.method = 'create';
			set.data.dataType = 'json';
			$.ajax(set);
		},
		getprofile: function(q) {
			var set = {
				url: "/api/user/account",
				data: q.data,
				method:'GET',
				dataType:'json',
				context: q.context,
				success: function(data, textStatus, XHR) {
					if(XHR.status == 201) {
						if(q.success) q.success(data, textStatus, XHR);
					}
					else this.error(XHR, data.textStatus, null);
				},
				error: function(XHR, textStatus, errorThrown) {
					if(q.error) q.error(XHR, textStatus, errorThrown);
				}
			};
			if(!set.data) set.data = {};
			set.data.method = 'getuser';
			set.data.dataType = 'json';
			$.ajax(set);
		}
	},
	authorization: {
		login: function(q) {
			var set = {
				url: "/user/authorization",
				data: q.data,
				method:'POST',
				dataType:'json',
				context: q.context,
				success: function(data, textStatus, XHR) {
					if(XHR.status == 201) {
						$.cookie("id", data.id, { path: '/', expires: 7 });
						$.cookie("hash", data.hash, { path: '/', expires: 7 });
						//$.cookie("account_id", data.account_id, { path: '/user', expires: 7 });
						if(q.success) q.success(data, textStatus, XHR);
						if(q.page_return) window.location=q.page_return;
					}
					else this.error(XHR, data.textStatus, null);
				},
				error: function(XHR, textStatus, errorThrown) {
					if(q.error) q.error(XHR, textStatus, errorThrown);
				}
			};
			if(!set.data) set.data = {};
			set.data.method = 'login';
			set.data.dataType = 'json';
			$.ajax(set);
		},
		logout: function(q) {
			var set = {
				url: "/user/authorizationLogout",
				data: {
					id: $.cookie("id"),
					method: 'logout',
					dataType: 'json'
				},
				method:'POST',
				dataType:'json',
				context: q.context,
				success: function(data, textStatus, XHR) {
					if(XHR.status == 201) {
						$.cookie("id", null, { path: '/' });
						$.cookie("hash", null, { path: '/' });
						if(q.success) q.success(data, textStatus, XHR);
						if(q.page_return) window.location=q.page_return;
						else location.reload();
					}
					else this.error(XHR, data.textStatus, null);
				},
				error: function(XHR, textStatus, errorThrown) {
					if(q.error) q.error(XHR, textStatus, errorThrown);
				}
			};
			$.ajax(set);
		}
	}
};

var tasks = {
		post: function(q) {
			var set = {
					url: "/tasks/post",
					data: q.data,
					method:'POST',
					cache:false,
					contentType: false,
					processData: false,
					dataType: 'json',
					type: 'POST',
					success: function(data, textStatus, XHR) {
						if(data.textStatus == "OK") {
							if(q.success) q.success(data, textStatus, XHR);
						}
						else this.error(XHR, data.textStatus, null);
					},
					error: function(XHR, textStatus, errorThrown) {
						if(q.error) q.error(XHR, textStatus, errorThrown);
					}
				};
				$.ajax(set);
		},
		update: function(q) {
			var set = {
					url: "/tasks/accept",
					data: q.data,
					method:'POST',
					dataType: 'json',
					type: 'POST',
					success: function(data, textStatus, XHR) {
						if(data.textStatus == "OK") {
							alert("Обновил");
						}
						else this.error(XHR, data.textStatus, null);
					},
					error: function(XHR, textStatus, errorThrown) {
						if(q.error) q.error(XHR, textStatus, errorThrown);
					}
				};
				$.ajax(set);
		},
}
