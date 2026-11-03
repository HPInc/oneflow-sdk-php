#!/usr/bin/python

__author__ = "HPInc."

import requests, hmac, hashlib, datetime
from requests.adapters import HTTPAdapter
from urllib3.util import Retry
from urllib.parse import unquote, urlsplit

class OneflowSDK:
    def __init__(self, url, token, secret, options=None):
        parsed_url = urlsplit(url)
        if parsed_url.scheme.lower() != "https" or not parsed_url.hostname:
            raise ValueError("OneflowSDK requires an HTTPS URL")

        self.url = url
        self.token = token
        self.secret = secret
        self.options = options

    def requests_retry_session(self):
        retries = getattr(self.options, "retries", 3)
        retryFactor = getattr(self.options, "retryFactor", 0.3 * 60)
        retryStatus = getattr(self.options, "retryStatus", [429])
        retryMethods = getattr(
            self.options,
            "retryMethods",
            ["HEAD", "GET", "PUT", "DELETE", "OPTIONS", "TRACE", "POST"],
        )

        session = requests.Session()
        retry = Retry(
            total=retries,
            read=retries,
            connect=retries,
            backoff_factor=retryFactor,
            status_forcelist=retryStatus,
            allowed_methods=retryMethods,
        )
        adapter = HTTPAdapter(max_retries=retry)
        session.mount("http://", adapter)
        session.mount("https://", adapter)
        return session

    def create_headers(self, method, path):
        timestamp = datetime.datetime.now().isoformat()
        string_to_sign = method.upper() + " " + unquote(path) + " " + timestamp
        signature = hmac.new(
            bytes(self.secret, "utf-8"), bytes(string_to_sign, "utf-8"), hashlib.sha256
        ).hexdigest()
        oneflow_auth = self.token + ":" + signature
        return {
            "content-type": "application/json",
            "x-oneflow-algorithm": "SHA256",
            "x-oneflow-authorization": oneflow_auth,
            "x-oneflow-date": timestamp,
        }

    def request(self, method, path, data=None, params=None):
        url = self.url + path
        headers = self.create_headers(method, path)
        session = self.requests_retry_session()
        result = session.request(
            method,
            url,
            data=data,
            params=params,
            headers=headers,
            allow_redirects=False,
        )
        return result.content
