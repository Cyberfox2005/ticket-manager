package com.ticketflow.scanner.data

import android.util.Base64
import com.ticketflow.scanner.domain.SecurityRepository
import java.security.KeyFactory
import java.security.Signature
import java.security.spec.X509EncodedKeySpec
import javax.inject.Inject

class SecurityRepositoryImpl @Inject constructor() : SecurityRepository {

    // Placeholder Public Key (X.509 format, Base64 encoded)
    private val publicKeyBase64 = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA75P... (Real Key Here)"

    override fun verifySignature(payload: String): Boolean {
        return try {
            val parts = payload.split("|")
            if (parts.size != 2) return false

            val data = parts[0]
            val signatureStr = parts[1]

            val signatureBytes = Base64.decode(signatureStr, Base64.DEFAULT)
            val publicBytes = Base64.decode(publicKeyBase64, Base64.DEFAULT)

            val keySpec = X509EncodedKeySpec(publicBytes)
            val keyFactory = KeyFactory.getInstance("RSA")
            val publicKey = keyFactory.generatePublic(keySpec)

            val sig = Signature.getInstance("SHA256withRSA")
            sig.initVerify(publicKey)
            sig.update(data.toByteArray())

            sig.verify(signatureBytes)
        } catch (e: Exception) {
            e.printStackTrace()
            false
        }
    }
}
